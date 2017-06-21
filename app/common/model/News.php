<?php
namespace app\common\model;

use think\Model;
use think\Db;

class News extends Model
{
    //增
    public function insert($data)
    {
        if(!$data || !is_array($data)){
            return false;
        }
        Db::name('news')->insert($data);
        return Db::name('news')->getLastInsID();
        
    }
    
    //单一查询
    public function find($id)
    {
        if(!$id || !is_numeric($id)){
            exception('ID不合法');
        }
        return Db::name('news')->where('news_id', $id)->find();
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
        return Db::name('news')->where('news_id', $id)->update($data);
    }
    
    /**
     * 分页显示文章
     */
    public function getNewsByPage($data=array(),$num)
    {
        if(!isset($data['status'])){
            $data['status'] = array('neq', -1);
        }
        return Db::name('news')->where($data)->order('listorder asc,news_id asc')->paginate($num);
    }
    
    /**
     * 修改文章状态
     */
    public function updateStatusById($id, $status)
    {
        if(!$id || !is_numeric($id)){
            exception('ID为空或不合法');
        }
        if(!is_numeric($status)){
            exception('数据不合法');
        }
        $data['status'] = $status;
        return Db::name('news')->where('news_id', $id)->update($data);
    }
    
    /**
     * 更新排序功能
     */
    public function updateListorderById($id, $listorder)
    {
        if(!$id || !is_numeric($id)){
            exception('ID不合法');
        }
        if(!is_numeric($listorder)){
            exception('数据不合法');
        }
        
        $data['listorder'] = $listorder;
        return Db::name('news')->where('news_id', $id)->update($data);
    }
    
    /**
     * 通过Id获取一组新闻
     */
    public function getNewsSetById($newsIds)
    {
        if(!is_array($newsIds)){
            exception('参数不合法');
        }
        $data = array(
            'news_id' => array('in', implode(',', $newsIds)),
        );
        return Db::name('news')->where($data)->select();
    }
    
    /**
     * 根据文章阅读数进行排行，获取文章内容
     */
    public function getNewsByRank($num=10)
    {
        $data['status'] = 1;
        return Db::name('news')->where($data)->order('count desc')->limit($num)->select();
    }
    
    /**
     * 获取正常发布文章总数
     */
    public function getNewsCount()
    {
        $data['status'] =1;
        return Db::name('news')->where($data)->count();
    }
}