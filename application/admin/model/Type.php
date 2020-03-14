<?php
namespace app\admin\model;
class Type
{
    //题材增删改
    public function addto($data){
        if (isset($data['id'])) {
            $where[] = ['is_delete','=',0];
            $where[] = ['id','=',$data['id']];
            $data['update_time']=time();
            //判断是否有删除字段
            if (isset($data['is_delete'])) {
                $data['delete_time'] = time();
            }
            return Db('admin_type')->where($where)->update($data);
        }
        $data['create_time']=time();
        return Db('admin_type')->insert($data);
    }

    //题材列表
    public function getList($data,$where = []){
        $datas = Db('admin_type')
            ->where('is_delete','=',0)
            ->where($where)
            ->page($data['p'],$data['total'])
            ->field('id,typename,grade,state')
            ->select();
        return $datas;
    }

    public function cartoonAdd($data){
        if (isset($data['id'])) {
            $where[] = ['id','=',$data['id']];
            $data['update_time']=time();
            //判断是否有删除字段
            if (isset($data['is_delete'])) {
                $data['delete_time'] = time();
            }
            return Db('admin_cartoon')->where($where)->update($data);
        }
        $data['create_time']=time();
        return Db('admin_cartoon')->insert($data);
    }

    //列表
    public function cartoonList($data,$where = []){
        $datas = Db('admin_cartoon')
            ->alias('c')
            ->join('admin_type t','t.id = c.type_id')
            ->where('c.is_delete','=',0)
            ->where($where)
            ->page($data['p'],$data['total'])
            ->field('c.id,.c.name,c.picture,c.content,c.progress,t.typename as type_id')
            ->select();
//            dump($datas);exit;
        foreach($datas as $k=>$v){
            if($v['progress'] == 0){
                $datas[$k]['progress'] = '连载';
            }else{
                $datas[$k]['progress'] = '完结';
            }
        }
        return $datas;
    }
}