<?php
namespace app\common\model;
use think\Db;
use think\Model;

class Position extends Model
{
    //单一查询
    public function find($id)
    {
        if(!$id || !is_numeric($id)){
            return false;
        }
        return Db::name('position')->where('id', $id)->find();
    }
    
    //增加
    public function insert($data)
    {
        if(!$data || !is_array($data)){
            return 0;
        }
        return Db::name('position')->insert($data);
    }
    
    //获取未删除的推荐位
    public function getPosition($data = array())
    {
        if(!is_array($data)){
            exception('条件格式错误');
        }
        $data['status'] = array('neq', -1);
        return Db::name('position')->where($data)->order('id asc')->select();
    }
    
    //获取正常状态的推荐位
    public function getNormalPosition()
    {
        $data['status'] = 1;
        return Db::name('position')->where($data)->order('id asc')->select();
    }
    
    //更新数据
    public function updateById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            excepition("数据不合法");
        }
        return Db::name('position')->where('id', $id)->update($data);
    }
    
    /**
     * 更新状态
     * @param unknown $id
     * @param unknown $status
     * @return number|string
     */
    public function updateStatusById($id, $status)
    {
        if(!$id || !is_numeric($id)){
            exception('ID不合法');
        }
        if(!is_numeric($status) || !in_array($status, array(-1,0,1))){
            excepition("状态不合法");
        }
        $data['status'] = $status;
        return Db::name('position')->where('id', $id)->update($data);
    }
    
    /**
     * 获取推荐位总数
     * @return number|string
     */
    public function getPositionCount()
    {
        $data['status'] =1;
        return Db::name('position')->where($data)->count();
    }
}