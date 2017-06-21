<?php
namespace app\index\controller;

use think\Request;
use think\Session;

class Detail extends Common
{
    public function index()
    {
        $request = Request::instance();
        $id = intval($request->param('id'));
        $news = model('News')->find($id);
        $content = model('NewsContent')->find($id);
        $news['content'] = htmlspecialchars_decode($content['content']);
        
        //增加一次阅读数
        $data['count'] = intval($news['count'] + 1);
        model("News")->updateById($id, $data);

        $this->assign([
            'news'   => $news,
            'catid'  => $news['catid'],
        ]);
        $this->right();
        return $this->fetch();
    }
    
    public function view()
    {
        $admin = Session::get('adminUser');
        if(!$admin){
            $this->error('您没有权限访问该页面', '/');
        }
        return $this->index();
    }
}