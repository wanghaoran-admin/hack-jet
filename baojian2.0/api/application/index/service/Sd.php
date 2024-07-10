<?php

namespace app\index\service;

class Sd
{
    /**
     * 检查sd配置
     * @param $params
     * @return array
     */
    public static function sdConfig($params)
    {
        $info = [];
        if (empty($params['sd_host'])) {
            $info['status'] = 1;
            $info['message'] = '请输入sd的链接';
            return $info;
        }
        if (strpos($params['sd_host'], 'http')) {
            $info['status'] = 1;
            $info['message'] = 'sd链接输入错误';
            return $info;
        }
        if (empty($params['sd_port'])) {
            $info['status'] = 1;
            $info['message'] = '请输入sd端口';
            return $info;

        }
        if ($params['is_https'] == '') {
            $https = 'http://';
        } else {
            $https = 'https://';
        }

        $url = $https . $params['sd_host'] . ':' . $params['sd_port'] . '/token';
        $header = array('Content-Type: application/json; charset=utf-8');
        $result = http_get($url, $header);
        if ($result === false) {
            $info['status'] = 1;
            $info['message'] = '请检查sd配置是否正确';
            return $info;
        }
        $info['status'] = 0;
        $info['url'] = $https . $params['sd_host'] . ':' . $params['sd_port'];
        return $info;
    }

    /**
     * sd出图---文生图
     * @param $params
     * @return array|false
     */
    public static function sdImage($params, $domain)
    {
        $num = $params['key'];
        unset($params['key']);
        $info = [];
        //sdapi链接
        $url = $domain . '/sdapi/v1/txt2img';
        $header = array('Content-Type: application/json; charset=utf-8');
        $result = http_post($url, $params, 200, $header, 'json');
        if ($result === false) {
            $info['status'] = 1;
            $info['message'] = '请检查sd或者重启';
            return $info;
        }
        $result = json_decode($result, true);
        if (!array_key_exists('images',$result)) {
            $info['status'] = 1;
            $info['message'] = '请检查sd或者重启';
            return $info;
        }
        $image = $result['images'];
        if (!file_exists(getPath() . 'images/' . date('Ymd'))) {
            mkdir(getPath() . 'images/' . date('Ymd'),0777,true);
        }
        $i = 1;
        foreach ($image as $value) {
            $base64_string = explode(',', $value);//截取data:image/png;base64, 这个逗号后的字符
            foreach ($base64_string as $v) {
                $data = base64_decode($v);
                $filename = getPath() . 'images/' . date('Ymd') . '/' . $num . '-' . $i++ . '-' . 'y-' . time() . '.png';
                file_put_contents($filename, $data);//写入文件并保存
                $res[] = str_replace(getPath() . 'images/','http://127.0.0.1:6891/',$filename);
            }

        }

        $res = array_chunk($res, 1);

        foreach ($res as $key => &$value) {
            $operation = ['top', 'bottom', 'left', 'right', 'big', 'small'];
            $value['url'] = $value[0];
            $size = getimagesize($value['url']);
            unset($res[$key][0]);
            $value['width'] = $size[0];
            $value['height'] = $size[1];
            $k = array_rand($operation, 1);//输出随机内容
            $value['operation'] = $operation[$k];
            $value['speed'] = 0.04;
            $result1[] = $value;
        }
        $info['status'] = 0;
        $info['result'] = $result1;
        return $info;
    }

    /**
     * 图生图高清修复
     * @param $params
     * @param $url
     * @return array|false
     */
    public static function imgToImage($params, $url)
    {
        $info = [];
        if (empty($params['image'])) {
            $info['status'] = 1;
            $info['message'] = '图未生成';
            return $info;
        }
        $file_name = explode('/',$params['image']);
        $file_name = end($file_name);
        $file_name = explode('.',$file_name);
        $file_name = $file_name[0];
        if (preg_match('/[a-zA-Z]/',$file_name)) {
            $file_name = explode('-',$file_name);
            $file_name = array_splice($file_name,0,-2);
            $file_name = implode('-',$file_name);
        }
        $size = getimagesize($params['image']);
        $image = file_get_contents($params['image']);
        $image = base64_encode($image);
        $header = array('Content-Type: application/json; charset=utf-8');
        $url = $url . '/sdapi/v1/img2img';
        $data = [];
        $data['init_images'] = [
            $image
        ];
        //$data['resize_mode'] = 1;
        $data['steps'] = $params['step'];//采样步数
        $data['cfg_scale'] = $params['cfg_scale'];//提示词相关性
        //$data['inpainting_fill'] = 0;
        //$data['inpaint_full_res'] = true;
        $data['denoising_strength'] = 0.7;
        //$data['eta'] = 1;
        //$data['include_init_images'] = true;
        if ($params['multiple'] < 2) {
            $multiple = 2;
        }else{
            $multiple = $params['multiple'];
        }
        $data['width'] = $size[0] * $multiple;//宽度
        $data['height'] = $size[1] * $multiple;//高度
        $data['batch_size'] = 1;//生成图片数量
        $data['seed'] = -1;//随机种子
        $data['restore_faces'] = false;
        $data['prompt'] = $params['prompt'];
        $data['negative_prompt'] = $params['negative_prompt'];
        $data['sampler_name'] = $params['sampler_name'];//采样方式
        $result = http_post($url, $data, 200, $header, 'json');
        if ($result === false) {
            $info['status'] = 1;
            $info['message'] = '生成失败,请检查sd';
            return $info;

        }
        $result = json_decode($result, true);

        if (!array_key_exists('images',$result)) {
            $info['status'] = 1;
            $info['message'] = 'CUDA内存不足';
            return $info;

        }
        $image = $result['images'];
        $base64_string = explode(',', $image[0]);//截取data:image/png;base64, 这个逗号后的字符
        $image_info = base64_decode($base64_string[0]);
        if (!file_exists(getPath() . 'images/' . date('Ymd'))) {
            mkdir(getPath() . 'images/' . date('Ymd'),0777,true);
        }
        $filename = getPath() . 'images/' . date('Ymd') . '/' . $file_name . '-hd-' . time() . '.png';
        file_put_contents($filename, $image_info);//写入文件并保存
        $images = [];
        $images['url'] = str_replace(getPath() . 'images/','http://127.0.0.1:6891/',$filename);
        $images['width'] = $data['width'];
        $images['height'] = $data['height'];
        $info['status'] = 0;
        $info['result'] = $images;
        return $info;

    }


    /**
     * 无损
     * @param $params
     * @param $url
     * @return array|false
     */
    public static function upscalersImg($params, $url)
    {
        $info = [];
        if (empty($params['image'])) {
            $info['status'] = 1;
            $info['message'] = '图未生成';
            return $info;
        }
        $file_name = explode('/',$params['image']);
        $file_name = end($file_name);
        $file_name = explode('.',$file_name);
        $file_name = $file_name[0];
        if (preg_match('/[a-zA-Z]/',$file_name)) {
            $file_name = explode('-',$file_name);
            $file_name = array_splice($file_name,0,-2);
            $file_name = implode('-',$file_name);
        }
        $size = getimagesize($params['image']);
        $image = file_get_contents($params['image']);
        $image = base64_encode($image);
        $header = array('Content-Type: application/json; charset=utf-8');
        $url = $url . '/sdapi/v1/extra-single-image';
        $data = [];
        $data['image'] = $image;
        $data['resize_mode'] = 0;
        $data['show_extras_results'] = true;
        $data['gfpgan_visibility'] = 0;
        $data['codeformer_visibility'] = 0;
        $data['codeformer_weight'] = 0;
        $data['upscaling_resize'] = 2;
        $data['upscaling_resize_w'] = $size[0];
        $data['upscaling_resize_h'] = $size[1];
        $data['upscaling_crop'] = true;
        $data['upscaler_1'] = $params['model'];
        $data['upscaler_2'] = $params['model'];
        $data['extras_upscaler_2_visibility'] = 1;
        $data['upscale_first'] = true;
        $result = http_post($url, $data, 200, $header, 'json');
        if ($result === false) {
            $info['status'] = 1;
            $info['message'] = '生成失败,请检查sd';
            return $info;

        }
        $result = json_decode($result, true);
        $image = $result['image'];
        $base64_string = explode(',', $image);//截取data:image/png;base64, 这个逗号后的字符
        $data = base64_decode($base64_string[0]);
        if (!file_exists(getPath() . 'images/' . date('Ymd'))) {
            mkdir(getPath() . 'images/' . date('Ymd'),0777,true);
        }
        $filename = getPath() . 'images/' . date('Ymd') . '/' . $file_name . '-ws-' . time() . '.png';
        file_put_contents($filename, $data);//写入文件并保存
        $images = [];
        $size = getimagesize(str_replace(getPath() . 'images/','http://127.0.0.1:6891/',$filename));
        $images['url'] = str_replace(getPath() . 'images/','http://127.0.0.1:6891/',$filename);
        $images['width'] = $size[0];
        $images['height'] = $size[1];
        $info['status'] = 0;
        $info['result'] = $images;
        return $info;
    }
}