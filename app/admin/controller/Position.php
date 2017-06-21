<?php
namespace app\admin\controller;
use think\Request;

class Position extends Common
{
    public function index()
    {
        $position = model('Position')->getPosition();
        $this->assign('position', $position); 
        $this->nav();
        return $this->fetch();
    }
    
    public function add(Request $request)
    {
        if($request->post()){
            if($request->post('name')== null){
                return show(0, "菜单名不能为空");
            }
            if($request->post('description')== null){
                return show(0, '模块名不能为空');
            }            
            
            if($request->post('id')){
                $data = $request->post();
                return $this->update($data);
            }
            
            //提交新增数据
            $data = $request->post();
            $data['create_time'] = time();
            $res = model('Position')->insert($data);
            if(!$res){
                return show(0, '新增失败');
            } else {
                return show(1, '新增成功');
            }
        } else {
            $this->nav();
            return $this->fetch();
        }
    }
    
    /**
     * 编辑推荐位页面
     * @param Request $request
     * @return unknown
     */
    public function edit(Request $request)
    {
        $id = intval($request->param('id'));
        $position = model('Position')->find($id);
        $this->assign('position', $position);
        $this->nav();
        return $this->fetch();
    }
    
    public function setStatus()
    {
        $request = Request::instance();
        $id = intval($request->post('id'));
        $status = intval($request->post('status'));
        try{
            $res = model('Position')->updateStatusById($id, $status);
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
     * 更新管理位内容
     * @param unknown $data
     * @return unknown
     */
    private function update($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $data['update_time'] = time();
        try{
            $res = model('Position')->updateById($id, $data);
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