<?php

namespace app\index\controller;

use think\Controller;
use app\index\service\Sd;
use think\Request;

class SdImage extends Base
{


    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE, post');// 表示只允许POST请求
        header('Access-Control-Allow-Headers:*');
    }

    public function config()
    {
        $data['code'] = 1;
        $data['show'] = 1;
        $data['msg'] = '';
        $data['data'] = [];
        return json($data);
    }

    public function index()
    {
        $data['code'] = 1;
        $data['show'] = 1;
        $data['msg'] = '';
        $data['data'] = [];
        return json($data);
    }

    /**
     * sd配置
     * @return \think\response\Json
     */
    public function getSdConfig()
    {
        $params = input('post.');
        $result = Sd::sdConfig($params);
        if ($result['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result['message'];
            $data['data'] = [];
            return json($data);
        }else{
            $data['code'] = 1;
            $data['show'] = 1;
            $data['msg'] = '配置成功';
            $data['data']['status'] = '配置成功';
            return json($data);
        }
    }

    /**
     * 获取无损放大模型
     * @return \think\response\Json
     */
    public function upscalers()
    {
        $params = input('post.');
        $result = Sd::sdConfig($params);
        if ($result['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result['message'];
            $data['data'] = [];
            return json($data);
        }
        $url = $result['url'] . '/sdapi/v1/upscalers';
        $header = array('Content-Type: application/json; charset=utf-8');

        $result = http_get($url, $header);

        if(strpos($result,'not found')){
            $result = [];
        }else{
            $result = json_decode($result, true);
        }
        $data['code'] = 1;
        $data['show'] = 1;
        $data['msg'] = '获取无损放大模型成功';
        $data['data'] = $result;
        return json($data);
    }

    /**
     * 文生图
     * @return \think\response\Json
     */
    public function sdImage()
    {
        $params = input('post.');
        $enable_hr = Request::instance()->param('enable_hr');

        if ($enable_hr) {
            $params['height'] = $params['height'] * 2;
            $params['width'] = $params['width'] * 2;
        }
        $params['enable_hr'] = false;
        $result = Sd::sdConfig($params);
        if ($result['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result['message'];
            $data['data'] = [];
            return json($data);
        }
        $result1 = Sd::sdImage($params,$result['url']);
        if ($result1['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result1['message'];
            $data['data'] = [];
            return json($data);
        }
        $data['code'] = 1;
        $data['show'] = 0;
        $data['msg'] = '';
        $data['data'] = $result1['result'];
        return json($data);
    }

    /**
     * 图生图
     * @return \think\response\Json
     */
    public function imgToImg()
    {
        $params = input('post.');
        $result = Sd::sdConfig($params);
        if ($result['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result['message'];
            $data['data'] = [];
            return json($data);
        }
        $result1 = Sd::imgToImage($params,$result['url']);
        if ($result1['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result1['message'];
            $data['data'] = [];
            return json($data);
        }
        $data['code'] = 1;
        $data['show'] = 0;
        $data['msg'] = '';
        $data['data'] = $result1['result'];
        return json($data);
    }

    /**
     * 无损
     * @return \think\response\Json
     */
    public function upscalersImg()
    {
        $params = input('post.');
        $result = Sd::sdConfig($params);
        if ($result['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result['message'];
            $data['data'] = [];
            return json($data);
        }
        $result1 = Sd::upscalersImg($params,$result['url']);
        if ($result1['status'] == 1) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = $result1['message'];
            $data['data'] = [];
            return json($data);
        }
        $data['code'] = 1;
        $data['show'] = 0;
        $data['msg'] = '';
        $data['data'] = $result1['result'];
        return json($data);
    }
}