<?php 
namespace app\admin\controller;
use think\Controller;             
use think\Request;

                                                                                                
class Admin extends Common
{
    public function index()
    {
        $adminlist = model('Admin')->getAdminList();
        $this->assign('adminlist', $adminlist);
        $this->nav();
        return $this->fetch();
    }
    
    public function add()
    {
        $request = Request::instance();
        if($request->post()){
            $data = $request->post();
            if(!$data['username']){
                return show(0, '用户名不能为空');
            }
            if(!$data['password']){
                return show(0, '密码不能为空 ');
            }
            if(!$data['realname']){
                return show(0, '真实名字不能为空');
            }
            $data['password'] = getMD5password($data['password']);
            $admin = model('Admin')->getAdminByUsername($data['username']);
            if($admin && $admin['status'] != -1){
                return show(0, '用户名已存在，请重新输入');
            }
            
            $res = model('Admin')->insert($data);
            if(!$res){
                return show(0, '添加失败');
            } else {
                return show(1, '添加成功');
            }
        } else {
            $this->nav();
            return $this->fetch();
        }
    }
    
    
    public function setStatus()
    {
        $request = Request::instance();
        $id = intval($request->post('id'));
        $status = intval($request->post('status'));
        
        try{
            $res = model('Admin')->updateStatusById($id, $status);
            if($res){
                return show(1, '操作成功');
            } else {
                return show(0, '操作失败');
            }
        } catch(\Exception $e){
            return show(0, $e->getMessage());
        }
    }
    
    public function personal()
    {
        $res = $this->getLoginUser();
        $adminUser = model('Admin')->find($res['admin_id']);
        $this->assign('user', $adminUser);
        $this->nav();
        return $this->fetch();
    }
    
    public function update()
    {
        $request = Request::instance();
        $data = $request->post();
        $id = $data['admin_id'];
        unset($data['admin_id']);
        try{
            $res= model('Admin')->updateById($id, $data);
            if(!$res){
                return show(0, '操作失败');
            } else {
                return show(1, '操作成功');
            }
        } catch (\Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    
}
?>