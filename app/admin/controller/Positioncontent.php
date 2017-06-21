<?php
namespace app\admin\controller;

use think\Request;

class Positioncontent extends Common
{
    public function index()
    {
        $request = Request::instance();
        $data = array();
        $positionId = intval($request->param('position_id'));
        $title = trim($request->param('title'));
        if($positionId && is_numeric($positionId)){
            $data['position_id'] = $positionId;
            $this->assign('positionId', $positionId);
        } else {
            $this->assign('positionId', '');
        }
        if($title){
            $data['title'] = array('like', '%'.$title.'%');
            $this->assign('title', $title);
        } else {
            $this->assign('title', '');
        }
        
        $positions = model('Position')->getNormalPosition();        
        $contents = model('PositionContent')->getPositioncontentByPage($data);
        
        $this->assign('positions', $positions);
        $this->assign('contents', $contents);
        $this->nav();
        return $this->fetch();
    }
    
    /**
     * 新增推荐位内容
     * @return unknown
     */
    public function add()
    {
        $request = Request::instance();
        $data = $request->post();
        if($data){
            if(!$data['title']){
                return show(0, '标题不能为空');
            }
            if(!$data['thumb']){
                return show(0, '请上传缩略图');
            }
            if(!$data['url']){
                if(!$data['news_id']){
                    return show(0, '请输入url地址或文章id');
                } else {
                    $news = model('News')->find($data['news_id']);
                    if(!$news){
                        return show(0, '文章id不存在，请输入正确的文章id');
                    }
                }
            }
            
            //更新
            if($data['id']){
                return $this->update($data);
            }
            
            $data['create_time'] = time();
            $res = model('PositionContent')->insert($data);
            if($res === false){
                return show(0, '添加失败');
            } else {
                return show(1, '添加成功');
            }
        } else {        
            $positions = model('Position')->getNormalPosition(); 
            $this->assign('positions', $positions);
            
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
        $id = $request->param('id');
        $positions = model('Position')->getNormalPosition();
        $this->assign('positions', $positions);
        
        $pcontent = model('PositionContent')->find($id);
        $this->assign('pcontent', $pcontent);
        $this->nav();
        return $this->fetch();
    }
    
    public function setStatus()
    {
        $request = Request::instance();
        $id = intval($request->param('id'));
        $status = intval($request->param('status'));
        try{
            $res = model('PositionContent')->updateStatusById($id, $status);
            if($res){
                return show(1, '操作成功');
            } else {
                return show(0, '操作失败');
            }
        } catch (\Exception $e){
            return show(0, $e->getMessage());
        }
    }
    
    /**
     * 推荐位内容排序功能
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
                    $res = model('PositionContent')->updateListorderById($id, $listorder);
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
    
    /**
     * 更新推荐位内容数据
     * @param unknown $data
     * @return unknown
     */
    private function update($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $data['update_time'] = time();
        try{
            $res = model('PositionContent')->updateById($id, $data);
            if($res){
                return show(1, '操作成功');
            } else {
                return show(0, '操作失败');
            }
        } catch (\Exception $e){
            return show(0, $e->getMessage());
        }
    }
}