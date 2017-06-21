<?php
namespace app\admin\controller;

use think\Request;

class Basic extends Common
{
    public function index()
    {
        $res = model('Basic')->select();
        $this->assign('basic', $res);
        $this->assign('type',1);
        
        $this->nav();
        return $this->fetch();
    } 
    
    public function add()
    {
        $request = Request::instance();
        if($request->param()){
            if(!$request->post('title')){
                return show(0, '站点信息不能为空');
            }
            if(!$request->post('keywords')){
                return show(0, '站点关键词不能为空');
            }
            if(!$request->post('description')){
                return show(0, '站点信息不能为空');
            }
            $data = $request->post();
            model('Basic')->saveCache($data);
            return show(1, '配置成功');
        } else {
            return show(0, '没有提交的数据');
        }
    }
    
    public function cache()
    {
        $this->assign('type', 2);
        
        $this->nav();
        return $this->fetch();
    }
}