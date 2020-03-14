<?php
namespace app\admin\controller;
use app\admin\controller\Base;
class Legend extends Base {
    //用户折线图
    public function auto() {
        if (empty($this->tokenget())){
            return show(false,'请先登录',401);
        }
        $filter = input('post.');
//        dump($filter);exit;
        if(empty($filter['type'])) {
            return show(false, 'type参数缺少',204);
        }
        if(empty($filter['start_time']) || empty($filter['end_time'])) {
            return show(false, '缺少参数开始时间或结束时间',204);
        }
        $data = Db('admin_count_dict')->select();
        $dates = [];
        $time = '';
        if($filter['type'] == 1) {
            $time = 86400; //天
        } elseif($filter['type'] == 2) {
            $time = 86400*7; //星期
        } elseif($filter['type'] == 3) {
            $time = 86400*30; //月
        } elseif($filter['type'] == 4) {
            $time = 86400*365; //年
        } else {
            return show(false, 'type参数中没有'.$filter['type']);
        }
        foreach($data as $v) {
            $dates = $this->getList($v, $filter, $time);
        }
        return show(true, $dates);
    }
    //处理折线图数据
    public function getList($title, $filter, $time) {
        if(!isset($title['table'])) {
            return '';
        }
        $data = Db($title['table'])->field('create_time')->select();
        $seart = $filter['start_time'];//开始时间
        $end = $filter['end_time'];//结束时间
        $xAxis = [];// X轴
        $legend = [];// Y轴
        $date_times=[];//时间 用于判断Y轴时间是否超出
        $xAxis['type'] =$filter['type']; //类型
        //计算for次数
        $number_of = ($end - $seart) / ($time);
        //循环出日期
        for ($i = 0;$i <= $number_of;$i ++) {
            $date = $seart + $i * $time;
            $date_times[] = $date;
            $arr[] =date('Y-m-d',$date);
        }
        //遍历日期
        foreach($date_times as $v) {
            $count = 0;
            //遍历数据库时间
            foreach ($data as $key => $value) {
                //是否大于开始时间和小于开始时间
                if($value['create_time'] < $v + $time && $value['create_time'] > $v) {
                    $count += 1;
                }
            }
            $legend[] = $count;
        }
        $xAxis['name'] = $title['content'];
        $xAxis['data']= $arr;
        $datas=['xAxis'=>$xAxis, 'series'=>$legend];
        return $datas;
    }
}