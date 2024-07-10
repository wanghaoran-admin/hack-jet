<?php

namespace app\index\controller;

use app\index\service\BaiDuApi;
use app\index\service\YouDaoYun;
use think\Controller;
use think\Request;

class Reasoning extends Base
{
    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE, post');// 表示只允许POST请求
        header('Access-Control-Allow-Headers:*');
    }

    /**
     * 推理
     * @return \think\response\Json
     */
    public function reasoning()
    {
        $params = input('post.');
        if (empty($params['text'])) {
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = '文案不能为空';
            $data['data'] = [];
            return json($data);
        }
        $result = BaiDuApi::extractionKeywords($params['text']);//关键词提取
        if ($result) {
            $en_keywords = YouDaoYun::create_request($result);//翻译关键词
        }else{
            $en_keywords = '';
        }
        $en_text = YouDaoYun::create_request($params['text']);//翻译原来文本
        $en_texts = ',' . $en_keywords . ',' . $en_text;
        $data1['text'] = $en_texts;
        $data['code'] = 1;
        $data['show'] = 0;
        $data['msg'] = '';
        $data['data'] = $data1;
        return json($data);
    }
}