<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function show($bool = true, $data = '', $code = 200, $err_data = [])
{
    header('Content-Type:application/json; charset=utf-8');
    if ($bool === null) {
        $bool = true;
    }
    if (!$data) {
        $data = array();
    }
    if ($code == []) {
        $code = 200;
    }

    if ($bool) {
        $return = array('info' => array('ok' => true, 'message' => (is_array($data) || is_object($data)) ? '' : $data), 'data' => $data, 'code' => $code);
    } else {
        $return = array('info' => array('ok' => false, 'message' => $data), 'code' => $code, 'data' => $err_data);
    }
    $str = json_encode($return, JSON_UNESCAPED_UNICODE);
    $str = str_replace('\\\\u', '\\u', $str);

    return $str;
}