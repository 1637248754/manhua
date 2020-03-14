<?php
namespace app\admin\model;
use \app\admin\controller\Base;
use think\Db;

class User extends Base
{
    public function getList($data,$where = []){
//         $arrays = [];
//         for($i=1;$i<=1500;$i++){
//           $arrays[$i] = [
//               'id'=>$i,
//               'username'=>$i,
//               'password'=>'e10adc3949ba59abbe56e057f20f883e',
//               'g_id'=>2,
//               'phone'=>$i,
//               'state'=>0
//           ];
//             $arrays[$i] = [
////                 'id'=>$i,
//                 'create_time'=>time(),
//                 'u_id'=>$i
//             ];
//         }
//         Db('admin_count_user')->insertAll($arrays);

      $datas = Db('admin_user')
          ->where('is_delete','=',0)
          ->where($where)
          ->page($data['p'],$data['total'])
          ->field('id,username,phone,state')
          ->select();
        return $datas;
    }
}