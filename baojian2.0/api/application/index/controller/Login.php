<?php

namespace app\index\controller;

use app\index\service\BaiDuApi;
use think\Cache;
use think\Controller;
use think\Request;

class Login extends Controller
{

    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE, post');// 表示只允许POST请求
        header('Access-Control-Allow-Headers:*');
    }


    /**
     * 登录
     * @return \think\response\Json
     */
    public function login()
    {
        $rand_code = Request::instance()->param('rand_code');//随机码
        if (!$rand_code) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = '请输入随机码';
            $data['data'] = [];
            return json($data);
        }

        $pc_device_id = getSn();//设备id

        $url = 'https://newapi.thed1g.com/api/ApiHuiHua/login?rand_code=' . $rand_code . '&pc_device_id=' . $pc_device_id;
        $res = BaiDuApi::http_get($url);
        $res = json_decode($res,true);

        if ($res['code'] == 1) {
            $result['login_token'] = $res['data']['login_token'];
            $data['code'] = 1;
            $data['show'] = 1;
            $data['msg'] = '登录成功';
            $data['data'] = $result;
            return json($data);
        }else{
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $res['msg'];
            $data['data'] = [];
            return json($data);
        }




    }

}