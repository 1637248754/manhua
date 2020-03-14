<?php

namespace app\admin\controller;

use \Firebase\JWT\JWT; //导入JWT
use \think\facade\Request;
use think\Model;
use think\Queue;
use think\facade\Cache; //引入缓存类
//最大公用类
class Base
{
    //增删改用户
    public function addto()
    {
        if (empty($this->tokenget())) {
            return show(false, '您好请先登录', 401);
        }
        $post = input('post.');
        if (empty($post)) {
            return show(false, '没有参数');
        }
        if (empty($post['username'])) {
            return show(false, '手机号，用户名，密码其中一个都不能为空');
        }
        if (isset($post['password'])) {
            $post['password'] = md5($post['password']);
        }
        $data = Model('Base')->addto($post);
        return $data ? show(true, '成功') : show(false, '失败');

    }

    //接收请求头token
    public function tokenget()
    {
        $header = Request::header('token');
        $ken = 'yin';
        if (isset($header)) {
            //捕获异常处理
            try {
                //解密jwt
                $info = JWT::decode($header, $ken, ["HS256"]);
                return json_encode($info);
            } catch (\Exception $e) {
                // 报错直接返回null
                return null;
            }
        }
    }

    //生成token
    public function token($data = '', $iss = '', $aud = '')
    {
        $key = "yin";  //这里是自定义的一个随机字串，应该写在config文件中的，解密时也会用，相当    于加密中常用的 盐  salt
        $token = [
            "iss" => $iss,  //签发者 可以为空
            "aud" => $aud, //面象的用户，可以为空
            "iat" => time(), //签发时间
            "nbf" => time(), //在什么时候jwt开始生效  （这里表示生成100秒后才生效）
            "exp" => time() + 86400 * 360, //过期时间
            "data" => $data //记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
        ];
        $jwt = JWT::encode($token, $key, "HS256"); //根据参数生成了 token
//        dump($jwt);exit;
        return show(true, ['data' => $data, "token" => $jwt], 200);
    }

    /**
     *管理员登录
     * type 0密码登录 1手机登录
     *
     */
    public function login()
    {
        $post = input('post.');
        //密码登录
        if ($post['type'] == 0) {
            if (empty($post['username']) || empty($post['password'])) {
                return show(false, '用户名或密码不能为空');
            }
            $where[] = ['username', '=', $post['username']];
            $where[] = ['password', '=', md5($post['password'])];

            $user = Model('Base')->login($where);
            if (isset($user)) {
                return $this->token($user);
            } else {
                return show(false, '用户名或密码错误');
            }
        } elseif ($post['type'] == 1) { //验证码登录
//            安装好redis在做
//             if(empty($post['phone'])){
//                 return show(false,'手机号不能为空');
//             }
//            $where[] = ['username','=',$post['phone']];
//            $user = Model('Base')->login($where);
//            $this->message(16620439442);
        }

    }

    //手机发送验证码
    public function message($phone = '')
    {
        $phone = empty($phone) ? input('post.phone') : $phone;
        //初始化必填
        //填写在开发者控制台首页上的Account Sid
        $options['accountsid'] = 'c5517d2204704980d8d5b7a60de91a92';
        //填写在开发者控制台首页上的Auth Token
        $options['token'] = 'dd0a44ba4db43d7e1b1e1db1c7df1f49';
        //初始化 $options必填
        $ucpass = new Ucpaas($options);
        $appid = "6a95eafa114944749bf379db3a4fcbe3";    //应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = "374304";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $param = $randnum = rand(500, 10000); //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $mobile = $phone;
        $uid = "";
        //70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        return $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid);
    }

    //处理上传的图片
    public function image($file = [])
    {
        if(empty($file)){
            $file = request()->file('file');
        }
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('D:/Home/vue-cli/mhxm/src/assets/uploads/' . time());
        if ($info) {
            $images = time() . '/' . str_replace("\\", "/", $info->getSaveName()); //替换上传的反斜杠
        } else {
            // 上传失败获取错误信息
            echo $file->getError();
        }
        return show(true,$images);
    }

    public function add(){
//       $post = input('post.');
       $arr = [
           'create_time'=>3,
           'u_id'=>3
       ];
       $url = 'app\admin\job\MultiTask@addp';
       $name = 'countuser';
       $data =Queue::push($url,$arr,$name);
       if($data){
         dump('执行完成');
       }
//       $a = Cache::store('redis')->get('name');
//         $a= Cache::store('redis')->set('name','11',3600);
//       dump($a);

    }

}
