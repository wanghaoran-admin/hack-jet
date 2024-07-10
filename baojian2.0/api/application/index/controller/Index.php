<?php

namespace app\index\controller;



use app\index\service\SrtText;
use think\Controller;
use think\Request;

class Index extends Base
{
    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE, post');// 表示只允许POST请求
        header('Access-Control-Allow-Headers:*');
    }

    /**
     * 上传图片
     * @return \think\response\Json
     */
    public function uploadImage()
    {

        $operation = ['top', 'bottom', 'left', 'right', 'big', 'small'];
        $key = Request::instance()->param('key');
        $domain = 'http://127.0.0.1:6891/';
        if (!file_exists(getPath() . 'images/' . date('Ymd') . '/')) {
            mkdir(getPath() . 'images/' . date('Ymd') . '/',0777,true);
        }
        $file = request()->file('file');
        $fileName = ($key + 1) . '-0-s-' . time() . '.png';
        if($file){
            $info = $file->move(getPath() . 'images/' . date('Ymd') . '/',$fileName);
            if($info){
                $url = $info->getSaveName();
                $url = str_replace('\\', '/', $url);
                $url = $domain . date('Ymd') . '/' . $url;
                $size = getimagesize($url);
                $result['width'] = $size[0];
                $result['height'] = $size[1];
                $result['speed'] = 0.04;
                $result['key'] = $key;
                $key1 = array_rand($operation, 1);//输出随机内容
                $result['operation'] = $operation[$key1];
                $result['uri'] = $url;
                $data = [];
                $data['code'] = 1;
                $data['show'] = 0;
                $data['msg'] = '获取成功';
                $data['data'] = $result;
                return json($data);
            }else{
                $data['code'] = 0;
                $data['show'] = 1;
                $data['msg'] = $file->getError();
                $data['data'] = [];
                return json($data);
            }
        }else{
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = '请上传图片';
            $data['data'] = [];
            return json($data);
        }
    }

    //上传字幕文件
    public function uploadSrt()
    {
        $file = request()->file('file');

        if (!file_exists(getPath() . 'srt/')) {
            mkdir(getPath() . 'srt/',0777,true);
        }
        $domain = 'http://127.0.0.1:6895/';
        //$fileName =
        if ($file) {
            $info = $file->move(getPath() . 'srt/','');
            if ($info) {
                $url = $info->getSaveName();
                $url = str_replace('\\', '/', $url);
                $url = $domain . $url;
                $result = SrtText::getInfo($url);
                $data = [];
                $data['code'] = 1;
                $data['show'] = 1;
                $data['msg'] = '处理成功,已自动保存';
                $data['data']['srtInfo'] = $result;
                $data['data']['imgInfo'] = [
                    'uri'=>$url
                ];
                if (!file_exists(getPath() . 'history/')) {
                    mkdir(getPath() . 'history/',0777,true);
                }
                $ext = date('YmdHis',time());
                $filename = getPath() . 'history/';
                $data_info = json_encode($result,JSON_UNESCAPED_UNICODE);
                $c_filename = $filename . 'ai-'. $info->getFilename() . '-' . $ext . '.json';
                if (file_exists($c_filename)) {
                    $c_filename = $filename . 'ai-'. $info->getFilename() . '(1)' . '.json';
                }
                $c_myfile = fopen($c_filename, "w") or die("Unable to open file!");
                fwrite($c_myfile, $data_info);
                fclose($c_myfile);
                return json($data);
            }else{
                $data['code'] = 0;
                $data['show'] = 1;
                $data['msg'] = '上传失败';
                $data['data'] = [];
                return json($data);
            }
        }else{
            $data['code'] = 0;
            $data['show'] = 1;
            $data['msg'] = '请上传正确的字幕文件';
            $data['data'] = [];
            return json($data);
        }
    }

    //历史列表
    public function histroyList()
    {
        $history_path= getPath() . 'history/';
        $list = scandir($history_path);
        $data['code'] = 1;
        $data['show'] = 0;
        $data['msg'] = '';
        $data['data'] = $list;
        return json($data);
    }

    public function dataInfo()
    {
        $file = Request::instance()->param('file');
    }
}
