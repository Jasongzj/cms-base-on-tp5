<?php
namespace app\common\model;
use think\Model;
use think\Db;

class PositionContent extends Model
{
    //分页获取推荐位内容
    public function getPositioncontentByPage($data=array())
    {
        if(!isset($data['status'])){
            $data['status'] = array('neq', -1);
        }
        return Db::name('position_content')->where($data)->order('listorder asc,position_id asc')->paginate(8);
    }
    
    //添加数据
    public function insert($data)
    {
        if(!$data || !is_array($data)){
            return false;
        }
        return Db::name('position_content')->insert($data);
    }
    
    //单一查询
    public function find($id)
    {
        if(!$id || !is_numeric($id)){
            return false;
        }
        return Db::name('position_content')->where('id', $id)->find();
    }
    
    /**
     * 更新数据
     * @param unknown $id
     * @param unknown $data
     * @return number|string
     */
    public function updateById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            exception('id为空或不合法');
        }
        if(!$data || !is_array($data)){
            exception('数据为空或不合法');
        }
        return Db::name('position_content')->where('id', $id)->update($data);        
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
            exception('id为空或不合法');
        }
        if(!is_numeric($status) || !in_array($status, array(-1,0,1))){
            exception('状态不合法');
        }
        $data['status'] = $status;
        return Db::name('position_content')->where('id', $id)->update($data);
    }
    
    /**
     * 更新排序功能
     * @param unknown $id
     * @param unknown $listorder
     * @return number|string
     */
    public function updateListorderById($id, $listorder)
    {
        if(!$id || !is_numeric($id)){
            exception('id为空或不合法');
        }
        if(!is_numeric($listorder)){
            exception('排序数据不合法');
        }
        $data['listorder'] = $listorder;
        return Db::name('position_content')->where('id', $id)->update($data);
    }
    
}
