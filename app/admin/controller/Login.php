<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Login extends Controller
{
    public function index()
    {
        if(Session::has('adminUser')){
            $this->redirect('/index.php/admin/index');
        }
        return $this->fetch();
    }
    
    public function check()
    {
        $username = input('post.username');
        $password = input('post.password');
        
        if(!trim($username)){
            return show(0, '用户名不能为空');
        }
        if(!trim($password)){
            return show(0, '请输入密码');
        }
        
        $res = model('Admin')->getAdminByUsername($username);
        if(!$res){
            return show(0, '用户名不存在');
        }
        
        if($res['password'] != getMD5password($password)){
            return show(0, '密码错误，请重新输入');
        }
        
        //记录最后登录时间
        $data = array('lastlogintime' => time());
        model('Admin')->updateById($res['admin_id'], $data);
        
        Session::set('adminUser', $res);
        return show(1, '登录成功');
    }
    
    public function loginOut()
    {
        Session::set('adminUser', null);
        $this->redirect('/index.php/admin/login');
    }
}