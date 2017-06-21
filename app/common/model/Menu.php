<?php
namespace app\common\model;

use think\Model;
use think\Db;

class Menu extends Model
{
    public function find($id)
    {
        return Db::name('menu')->where('menu_id', $id)->find();
    }
    
    public function insert($data)
    {
        if(!$data || !is_array($data)){
            return 0;
        }
        return Db::name('menu')->insert($data);
    }
    
    /**
     * 通过id更新数据
     * @param unknown $id
     * @param unknown $data
     * @return number|string
     */
    public function updateById($id, $data)
    {
        if(!$id || !is_numeric($id)){
            exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            excepition("数据不合法");
        }
        return Db::name('menu')->where('menu_id', $id)->update($data);
    }
    
    /**
     * 分页展示菜单列表
     */
    public function getMenuByPage($cond=array())
    {
        $cond['status'] = array('neq', -1);
        $res = Db::name('menu')->where($cond)->order('listorder asc,menu_id asc')->paginate(5);
        return $res;
    }
    
    /**
     * 更新状态
     * @param unknown $id
     * @param unknown $status
     * @return number|string
     */
    public function updateStatusById($id, $status){
        if(!$id || !is_numeric($id)){
            exception('ID不合法');
        }
        if(!is_numeric($status) || !in_array($status, array(-1,0,1))){
            exception('状态不合法');
        }
        $data['status'] = $status;
        return Db::name('menu')->where('menu_id', $id)->update($data);
    }
    
    /**
     * 更新排序
     * @param unknown $id
     * @param unknown $listorder
     * @return number|string
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
        return Db::name('menu')->where('menu_id', $id)->update($data);
    }
    
    /**
     * 获取后台菜单
     */
    public function getBackendMenu()
    {
        $data = array(
            'status' => array('neq',-1),
            'type'   => 1,
        );
        return Db::name('menu')->where($data)->select();
    }
    
    /**
     * 获取正常状态的前端导航
     */
    public  function getFrontMenu()
    {
        $data = array(
            'status' => 1,
            'type'   => 2,
        );
        return Db::name('menu')->where($data)->select();
    }
}