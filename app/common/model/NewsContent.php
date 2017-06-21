<?php
namespace app\common\model;
use think\Db;
use think\Model;

class NewsContent extends Model
{
    //增
    public function insert($data)
    {
        if(!$data || !is_array($data)){
            return false;
        }
        return Db::name('news_content')->insert($data);
    }
    
    //单一查询
    public function find($id)
    {
        if(!$id || !is_numeric($id)){
            exception('ID不合法');
        }
        return Db::name('news_content')->where('news_id', $id)->find();
    }
    
    //更新
    public function updateById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            exception('ID为空或不合法');
        }
        if(!$data || !is_array($data)){
            exception('数据为空或不合法');
        }
        return Db::name('news_content')->where('news_id', $id)->update($data);
    }
}