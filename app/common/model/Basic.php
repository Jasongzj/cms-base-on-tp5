<?php
namespace app\common\model;

use think\Model;

class Basic extends Model
{
    public function saveCache($data)
    {
        if(!$data){
            exception('没有提交的数据');
        }
        $cache = cache('basic_web_config', $data);
        return $cache;
    }
    
    public function select()
    {
        return cache('basic_web_config');
    }
}