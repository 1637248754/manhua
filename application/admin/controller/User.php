<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\console\command\make\Model;

class User extends Base{
    //用户查询
   public function getList(){
       if (empty($this->tokenget())){
          return show(false,'请先登录',401);
       }
       $post = input('post.');
       //判断是否有页数
       if(empty($post['p'])){
           $post['p'] = 1;
       }
       if(empty($post['total'])){
           $post['total'] = 10;
       }
       $where = [];
       //判断是否有搜索语句
       if(!empty($post['username'])){
         $where[] = ['username', 'like','%'.$post['username'].'%'];
       }
       if(!empty($post['phone'])){
         $where[] = ['phone', 'like','%'.$post['phone'].'%'];
       }
       $total = Db('admin_user')->where($where)->count();
       $data = Model('user')->getList($post,$where);
       $datas= [
           'data'=>$data,
           'total'=>$total
       ];
      return show(true,$datas);
   }
}