<?php
namespace app\admin\controller;

use think\Request;

class Content extends Common
{
    public function index()
    {
        $data = array();
                
               
        if(input('catid')){
            $data['catid'] = intval(input('catid'));
            $this->assign('catid', $data['catid']);
        } else {
            $this->assign('catid', -100);
        }
        
        if(input('title')){
            $data['title'] = array('like', '%'.input('title').'%');
            $this->assign('title', input('title'));
        } else {
            $this->assign('title', '');
        }
        
        //获取前端导航
        $frontMenu = model('Menu')->getFrontMenu();
        $newslist = model('News')->getNewsByPage($data, 10);
        $positions = model('Position')->getNormalPosition();
        $this->assign('frontMenu', $frontMenu);
        $this->assign('newslist', $newslist);
        $this->assign('positions', $positions);
        $this->nav();
        return $this->fetch();
    }
    
    /**
     * 添加文章功能
     * @return unknown
     */
    public function add()
    {
        $request = Request::instance();
        if($request->post()){
            
            if(empty($request->post('title'))){
                return show(0, '标题不能为空');
            }
            if(empty($request->post('small_title'))){
                return show(0, '短标题不能为空');
            }            
            if(empty($request->post('content'))){
                return show(0, '内容不能为空');
            }
            if(empty($request->post('description'))){
                return show(0, '描述不能为空');
            }
            
            if($request->post('news_id')){
                $data = $request->post();
                return $this->update($data);
            }
            
            //后续改用事务，保证两张表同时更新
            $data = $request->except('content', 'post');
            $newsId = model('News')->insert($data);
            if($newsId){
                $content['news_id'] = $newsId;
                $content['content'] = $request->post('content');                
                $res = model('NewsContent')->insert($content);
                if($res){
                    return show(1, '操作成功');
                } else {
                    return show(0, '主表操作成功，副表操作失败');
                }
            } else {
                return show(0, '操作失败');
            }
        } else {
            $sources = config('SOURCE');
            $frontMenu = model('Menu')->getFrontMenu();
            
            $this->assign('frontMenu', $frontMenu);
            $this->assign('sources', $sources);
            $this->nav();
            return $this->fetch();
        }
    }
    
    /**
     * 编辑页面
     * @return unknown
     */
    public function edit()
    {
        $request = Request::instance();
        $id = intval($request->param('id'));
        $news = model('News')->find($id);
        $newsContent = model('NewsContent')->find($id);
        $news['content'] = $newsContent['content'];
        
        //赋值：来源和栏目
        $sources = config('SOURCE');
        $frontMenu = model('Menu')->getFrontMenu();        
        $this->assign('frontMenu', $frontMenu);
        $this->assign('sources', $sources);
        
        $this->assign('news', $news);
        $this->nav();
        return $this->fetch();
    }
    
    /**
     * 删除文章，修改文章状态
     */
    public function setStatus()
    {
        $request = Request::instance();
        $id = intval($request->param('id'));
        $status = intval($request->param('status'));
        try{
            $res = model('News')->updateStatusById($id, $status);
            if($res){
                return show(1, '操作成功');
            } else {
                return show(0, '操作失败');
            }
        } catch(\Exception $e){
            return show(0, $e->getMessage());
        }        
    }
    
    /**
     * 更新排序
     */
    public function listorder()
    {
        $request = Request::instance();
        $errors = array();
        
        $jumpUrl = $request->server('HTTP_REFERER');
        $data = $request->post();
        if($data['listorder']){
            try{
                foreach ($data['listorder'] as $id => $listorder){
                    $res = model('News')->updateListorderById($id, $listorder);
                    if($res === false){
                        $errors[] = $id;
                    }
                }
            } catch(\Exception $e){
                return show(0, $e->getMessage());
            }
            if($errors){
                return show(0, '排序失败-'.implode(',', $errors), array('jump_url' => $jumpUrl));
            } else {
                return show(1, '排序成功', array('jump_url' => $jumpUrl));
            }
        } else {
            return show(0, '没有提交的数据', array('jump_url' => $jumpUrl));
        }
            
    }
    
    public function push()
    {
        $request = Request::instance();
        $jumpUrl = $request->server('HTTP_REFERER');
        $data = $request->post();
        $error = array();
        
        try{
            if(!isset($data['push']) || !is_array($data['push'])){
                return show(0, "请选择需要放至推荐位的文章");
            }
            if(!isset($data['position_id'])){
                return show(0, "没有选择推荐位");
            }
            $positionId = $data['position_id'];
            $newsId = $data['push'];
            $newsSet = model('News')->getNewsSetById($newsId); 
            if(!$newsSet){
                return show(0, '没有相关内容');
            }
            
            //遍历新闻组，将每条新闻插入推荐位中
            foreach ($newsSet as $news){
                $data = array(
                    'position_id' => $positionId,
                    'title'       => $news['title'],
                    'thumb'       => $news['thumb'],
                    'news_id'     => $news['news_id'],
                    'status'      => 1,
                    'create_time' => time(),
                );
                $res = model('PositionContent')->insert($data);
                if(!$res){
                    $error[] = $news['news_id'];
                }
            }
        } catch (\Exception $e){
            return show(0, $e->getMessage());
        }
        if(!$error){
            return show(1, '推荐成功', array('jump_url' => $jumpUrl));
        } else {
            return show(0, '推荐失败-'.implode(',', $error), array('jump_url' => $jumpUrl));
        }
    }
    
    /**
     * 文章内容更新
     */
    private function update($news)
    {
        
        //后续改用事务，保证两张表同时更新
        $id = intval($news['news_id']);
        unset($news['news_id']);
        
        $newsContent['content'] = $news['content'];
        unset($news['content']);
        
        try {
            $res = model('News')->updateById($id, $news);
            if($res){
                $res2 = model('NewsContent')->updateById($id, $newsContent);
                if($res2){
                    return show(1, '更新成功');
                } else {
                    return show(0, '主表更新成功，副表更新失败');
                }
            } else {
                return show(0, '更新失败');
            }            
        } catch (\Exception $e){
            return show(0,$e->getMessage());
        } 
    }
}