<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Common extends Controller
{
    public function __construct()
    {
        //继承Controller的构造函数并调用初始化方法；
        parent::__construct();
        $this->_init();
    }
    
    /**
    * 初始化
    * @return:  return_type
    */
    private function _init()
    {
        //判断是否登录
        $isLogin = $this->isLogin();
        //如果未登录则跳转到登录页面
        if(!$isLogin){
            $this->redirect('/index.php/admin/login');
        }
    }
    
    /**
     * 判断是否登录
     * @return boolean
     */
    public function isLogin()
    {
        $user = $this->getLoginUser();
        if(!$user && !is_array($user)){
            return false;
        } else {
            return true;
        }        
    }
    
    /**
     * 获取session中的登录信息
     * @return mixed|NULL|unknown|array
     */
    public function getLoginUser()
    {
        return Session::get('adminUser');
    }
    
    
    /**
     * 赋值到nav模版
     * @return unknown
     */
    protected function nav()
    {
        $navs = model('Menu')->getBackendMenu();
        $index = 'index';
        $user = $this->getLoginUser();
        $username = $user['username'];
        $this->assign('username', $username);
        $this->assign('index', $index);
        $this->assign('navs', $navs);
        return $this->fetch();
    }
}