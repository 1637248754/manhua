<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Model;
use think\facade\Cookie;

class Type extends Base {
   //题材增删改
   public function auto(){
       $post = input('post.');
//       dump($post);
       if (empty($post)) {
           return show(false, '没有参数');
       }
       if (empty($post['typename'])) {
           return show(false, '题材不能为空');
       }
       $data = Model('Type')->addto($post);
//       dump($data);exit;
       return $data?show(true,'成功'):show(false,'失败');
   }

    //题材列表
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
        $where[] = ['is_delete','=',0];
        //判断是否有搜索语句
        if(!empty($post['typename'])){
            $where[] = ['typename', 'like','%'.$post['typename'].'%'];
        }
        if(isset($post['grade'])){
            $where[] = ['grade','=',$post['grade']];
        }
        $total = Db('admin_type')->where($where)->count(); //统计数据(废话)
        $data = Model('type')->getList($post,$where);
        $datas= [
            'data'=>$data,
            'total'=>$total
        ];
        return show(true,$datas,200);
    }

    //修改页面题材
    public function getLists(){
        if (empty($this->tokenget())){
            return show(false,'请先登录',401);
        }
        $post = input('post.');
        $data = Db('admin_type')
            ->where('is_delete','=',0)
            ->field('id as value,typename as label')
            ->select();
        return $data?show(true,$data):show(true,'抱歉当前没有题材');

    }

    //漫画增删改
    public function cartoonAdd(){
        if (empty($this->tokenget())){
            return show(false,'请先登录',401);
        }
        $post = input('post.');
        if(empty($post['name'])){
          return show(false,'漫画名称为空');
        }
        if(empty($post['content'])){
            return show(false,'漫画介绍为空');
        }
//        if(empty($post['type_id'])){
//            return show(false,'题材不能为空');
//        }
       $data = Model('type')->cartoonAdd($post);
       return $data?show(true,'数据成功',200):show(false,'数据失败');
    }

    //漫画列表
    public function cartoonList(){
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
        if(!empty($post['name'])){
            $where[] = ['name', 'like','%'.$post['name'].'%'];
        }
        $total = Db('admin_cartoon')->where($where)->count(); //统计数据(废话)
        $data = Model('type')->cartoonList($post,$where);
        $datas= [
            'data'=>$data,
            'total'=>$total
        ];
        return show(true,$datas,200);
    }

}