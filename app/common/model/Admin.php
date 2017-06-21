<?php
    namespace app\common\model;
    use think\Model;
use think\Db;
                    
    class Admin extends Model
    {
        /**
         * 通过用户名获取数据表中信息
         * @param unknown $username
         * @return 整行数据
         */        
        public function getAdminByUsername($username)
        {
            $Admin = new Admin();
            $result = $Admin->where('username', $username)->find();
            return $result;
        }
        
        /**
         * 通过id获取数据表中信息
         * @param unknown $id
         * @return array|\think\db\false|PDOStatement|string|\think\Model
         */
        public function find($id)
        {
            return Db::name('admin')->where('admin_id', $id)->find();
        }
        
        /**
        * 获取管理员数据，用分页进行显示
        * @param: $num integer 每页显示的条数
        * @return:  array
        */
        public function getAdminList()
        {
            return Db::name('admin')->paginate(10);
        }
        
        
        //新增数据
        public function insert($data)
        {          
            if(!$data || !is_array($data)){
                return 0;
            }
            return Db::name('admin')->insert($data);           
        }
        
        //更新数据
        public function updateById($id, $data)
        {
            if(!$id || !is_numeric($id)){
                exception('Id为空或不合法');
            }
            if(!$data || !is_array($data)){
                exception('数据为空或不合法');
            }
            return Db::name('admin')->where('admin_id', $id)->update($data);
        }
        
        //删除数据
        public function deleteById($id)
        {
            return Db::name('admin')->where('admin_id', $id)->delete();
        }
        
        //更新状态
        public function updateStatusById($id, $status)
        {
            if(!$id || !is_numeric($id)){
                exception('ID为空或不合法');
            }
            $data['status'] = $status;
            return Db::name('admin')->where('admin_id', $id)->update($data);
        }
        
        /**
         * 获取今日登录用户数
         */
        public function getLoginUserCount()
        {
            $time = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $data = array(
                'status' => 1,
                'lastlogintime' => array("gt", $time),
            );
            $res = Db::name('admin')->where($data)->count();
            return $res;
        }
    }

?>