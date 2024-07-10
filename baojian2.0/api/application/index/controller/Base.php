<?php

namespace app\index\controller;

use app\index\service\BaiDuApi;
use think\Controller;
use think\Request;

class Base extends Controller
{



    public function __construct(Request $request = null)
    {

        $token = Request::instance()->param('token');
        if (!$token) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = '无效请求';
            $data['data'] = [];
            return json($data);exit();
        }

        $url = 'https://newapi.thed1g.com/api/ApiHuiHua/login?token=' . $token;


        $res = BaiDuApi::http_get($url);
        $res = json_decode($res,true);

        if ($res['code'] == 1) {
            $this->rand_code = $res['data'];
        }else{
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $res['msg'];
            $data['data'] = [];
            return json($data);exit();
        }
    }
}