<?php
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{
    public function __construct()
    {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct();
    }
    
    public function right()
    {
        //获取右侧广告位内容
        $advPic = model('PositionContent')->getPositioncontentByPage(array('status' => 1, 'position_id' => 5,));

        //获取文章排行
        $newsRank = model('News')->getNewsByRank();
        $this->assign([
            'advNews'      => $advPic,
            'newsRank'     => $newsRank,
        ]);
        
        return $this->fetch();
    }
    
}