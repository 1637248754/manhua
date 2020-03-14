<?php
namespace app\admin\model;
use think\queue\Job;
class Base
{
   public function addto($data){
       if (isset($data['id'])) {
           $where[] = ['is_delete','=',0];
           $where[] = ['id','=',$data['id']];
           $data['update_time']=time();
           //判断是否有删除字段
           if (isset($data['is_delete'])) {
               $data['delete_time'] = time();
           }
           return Db('admin_user')->where($where)->update($data);
       }
       $data['create_time']=time();
       $user = Db('admin_user')
           ->where('username','=',$data['username'])
           ->count();
       if($user > 0){
           return false;
       }elseif(empty($data['password']) || empty($data['phone'])){
          show(false,'手机和密码不能为空');
       }
       return Db('admin_user')->insert($data);
   }

   //管理员登录
   public function login($where = []){
     return Db('admin_user')
           ->where($where)
           ->field('is_delete,state,password,g_id',true)
           ->find();

   }
}
