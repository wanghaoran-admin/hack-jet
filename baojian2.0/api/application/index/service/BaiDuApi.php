<?php

namespace app\index\service;


class BaiDuApi
{

    public static function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
    /**
     * 获取token
     * @return mixed
     */
    public static function getToken()
    {
          $url = 'https://newapi.thed1g.com/api/ApiHuiHua/getBaiDuToken';
          $token = self::http_get($url);
          return $token;
    }


    /**
     * 提取关键词
     * @param $text
     * @return string
     */
    public static function extractionKeywords($text)
    {
        sleep(1);
        $header = array('Content-Type: application/json');
        $token = self::getToken();
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/txt_keywords_extraction?access_token=' . $token . '&charset=UTF-8';
        $data = [];
        $data['text'] = [$text];
        $result = http_post($url,$data,30,$header,'json');
        $result = json_decode($result,true);
        if (array_key_exists('results',$result)) {
            $res =$result['results'];
            $keywords = array_column($res,'word');
            $keywords = implode(',',$keywords);
            return $keywords;
        }else{
            return false;
        }

    }

    /**
     * 文章标题生成
     * @param $doc
     * @return mixed
     */
    public static function titlepredictor($doc)
    {
        $header = array('Content-Type: application/json');
        $token = self::getToken();
        $url = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/titlepredictor?access_token=' . $token . '&charset=UTF-8';
        $data = [];
        $data['doc'] =$doc;
        $result = http_post($url,$data,30,$header,'json');
        $result = json_decode($result,true);
        $result = $result['reference_titles'];
        $keywords = array_column($result,'title');
        $keywords = implode(',',$keywords);
        return $keywords;
    }
}