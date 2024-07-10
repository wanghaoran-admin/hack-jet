<?php

namespace app\index\service;

use think\Log;

class YouDaoYun
{
    /**
     * 生成参数
     * @param $param
     * @param $appKey
     * @param $appSecret
     * @return mixed
     */
    public static function add_auth_params($param, $appKey, $appSecret)
    {
        if (array_key_exists('q', $param)) {
            $q = $param['q'];
        } else {
            $q = $param['img'];
        }
        $salt = self::create_uuid();
        $curtime = strtotime("now");
        $sign = self::calculate_sign($appKey, $appSecret, $q, $salt, $curtime);
        $param['appKey'] = $appKey;
        $param['salt'] = $salt;
        $param["curtime"] = $curtime;
        $param['signType'] = 'v3';
        $param['sign'] = $sign;
        return $param;
    }

    /**
     * //创建uuid
     * @return string
     */
    public static function create_uuid()
    {
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $uuid;
    }

    /**
     * 创建签名
     * @param $appKey
     * @param $appSecret
     * @param $q
     * @param $salt
     * @param $curtime
     * @return string
     */
    public static function calculate_sign($appKey, $appSecret, $q, $salt, $curtime)
    {
        $strSrc = $appKey . self::get_input($q) . $salt . $curtime . $appSecret;
        return hash("sha256", $strSrc);
    }

    public static function get_input($q)
    {
        if (empty($q)) {
            return null;
        }
        $len = mb_strlen($q, 'utf-8');
        if ($len > 20 ) {
            return mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len);
        }else{
            return $q;
        }
    }



    public static function create_request($q)
    {
        $from = "zh-CHS";
        $to = "en";
        //$vocab_id = "B720D0C44920433291F1A9915784A8B3";

        $params = array('q' => $q,
            'from' => $from,
            'to' => $to,

        );
        $url = 'https://openapi.youdao.com/api';
        $params = self::add_auth_params($params, '5e3859d8902b1973', '1dwuManvr62VQnrmxunRuCUja47HOe6B');
        $header = array('charset=UTF-8');
        $result = http_post($url,$params,30,$header);
        $result = json_decode($result,true);

        if (!array_key_exists('translation',$result)) {
            sleep(1);
            $from = "zh-CHS";
            $to = "en";
            //$vocab_id = "B720D0C44920433291F1A9915784A8B3";

            $params = array('q' => $q,
                'from' => $from,
                'to' => $to,

            );
            $url = 'https://openapi.youdao.com/api';
            $params = self::add_auth_params($params, '5e3859d8902b1973', '1dwuManvr62VQnrmxunRuCUja47HOe6B');
            $header = array('charset=UTF-8');
            $result = http_post($url,$params,30,$header);
            $result = json_decode($result,true);
        }
        return $result['translation'][0];

    }

    public static function do_call($url, $method, $header, $param, $expectContentType, $timeout = 3000)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        $data = http_build_query($param);
        if ($method == 'post') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } else if ($method == 'get') {
            $url = $url . '?' . $data;
        } else {
            print 'http method not support';
            return null;
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        $r = curl_exec($curl);
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
        if (strpos($contentType, $expectContentType) === false) {
            echo $r;
            $r = null;
        }
        curl_close($curl);
        return $r;
    }
}