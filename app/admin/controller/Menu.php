<?php
namespace app\admin\controller;
use think\Request;

class Menu extends Common
{
    public function index()
    {
        
        $data = array();
        if(in_array(input('type'), array(1,2))){
            $data['type'] = input('type');
            $this->assign('type', input('type'));
        } else {
            $this->assign('type', 0);
        }
        $menus = model('menu')->getMenuByPage($data);
        $this->assign('menus', $menus);
        $this->nav();
        return $this->fetch();
    }
    
    public function add(Request $request)
    {
        if($request->post()){
            if($request->post('name')== null){
                return show(0, "菜单名不能为空");
            }
            if($request->post('m')== null){
                return show(0, '模块名不能为空');
            }
            if($request->post('c')== null){
                return show(0, '控制器名不能为空');
            }
            if($request->post('f')== null){
                return show(0, '方法名不能为空');
            }
            
            if($request->post('menu_id')){
                $data = $request->post();
                return $this->update($data);
            }
            
            //提交新增数据
            $data = $request->post();
            $res = model('Menu')->insert($data);
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
    
    public function edit(Request $request)
    {
        $id = intval($request->param('id'));
        $menu = model('Menu')->find($id);
        $this->assign('menu', $menu);
        $this->nav();
        return $this->fetch();
    }
    
    public function setStatus(Request $request)
    {
        if($request->post()){
            try{
                $id = intval($request->post('id'));
                $status = intval($request->post('status'));
                $res = model('Menu')->updateStatusById($id, $status);
                if(!$res){
                    return show(0, '操作失败');
                } else {
                    return show(1, '操作成功');
                }
            } catch (\Exception $e) {
                return show(0, $e->getMessage());
            }
        } else {
            return show(0, '没有提交的数据');
        }
    }
    
    public function listorder(Request $request)
    {
        $data = $request->post();
        $errors = array();
        $jumpUrl = $request->server('HTTP_REFERER');
        if($data['listorder']){
            try{
                foreach ($data['listorder'] as $id => $listorder){
                    $res = model('Menu')->updateListorderById($id, $listorder);
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
   
    
    private function update($data)
    {
        $id = intval($data['menu_id']);
        unset($data['menu_id']);
        
        try {
            $res = model('Menu')->updateById($id, $data);
            if(!$res){
                return show(0, '更新失败');
            } else {
                return show(1, '更新成功');
            }
            
        } catch (\Exception $e){
            return show(0,$e->getMessage());
        }        
    }
    
    
}