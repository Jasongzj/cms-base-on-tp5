<?php
namespace app\index\controller;

use think\Request;

class Cate extends Common
{
    public function index()
    {
        $request = Request::instance();
        $catid = $request->param('id');
        $newsList = model('News')->getNewsByPage(array('catid' => $catid, 'status' => 1),10);
        
        $this->assign([
            'newsList' => $newsList,
            'catid'    => $catid,
        ]);
        $this->right();
        return $this->fetch();
    }
}