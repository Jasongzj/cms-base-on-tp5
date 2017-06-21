<?php
namespace app\index\controller;
use think\Controller;
use think\Request;


class Index extends Common
{
    public function index()
    {
        //获取推荐内容
        $topPic = model('PositionContent')->getPositioncontentByPage(array('status' => 1, 'position_id' => 2));        
        $topSmPic = model('PositionContent')->getPositioncontentByPage(array('status' => 1, 'position_id' => 3));                
        $advPic = model('PositionContent')->getPositioncontentByPage(array('status' => 1, 'position_id' => 5, 'url' => array('neq', ''), 'news_id' => array('neq', '')));

        
        //获取文章排行
        $newsRank = model('News')->getNewsByRank();
        
        //获取文章列表
        $listNews = model('News')->getNewsByPage(array('status' => 1, 'thumb' => array('neq', '')), 10);
        
        $this->assign([
            'topPicNews'   => $topPic,
            'topSmPicNews' => $topSmPic,
            'listNews'     => $listNews,
            'catid'        => 0,
        ]);
        
        $this->right();
        return $this->fetch();
    }
    
}
