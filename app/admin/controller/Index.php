<?php
namespace app\admin\controller;

class Index extends Common
{
    public function index()
    {
        //获取阅读量最高的文章
        $newsMaxCount = model('News')->getNewsByRank(1);
        //获取文章总数
        $newsCount = model('News')->getNewsCount();
        //获取推荐位总数
        $positionCount = model('Position')->getPositionCount();
        //获取今日登录用户数
        $loginUserCount = model('Admin')->getLoginUserCount();
        
        
        $this->assign([
            'newsCount'      => $newsCount,
            'newsMaxCount'   => $newsMaxCount[0],
            'positionCount'  => $positionCount,
            'loginUserCount' => $loginUserCount,
        ]);
        $this->nav();
        return $this->fetch();
    }
    
}