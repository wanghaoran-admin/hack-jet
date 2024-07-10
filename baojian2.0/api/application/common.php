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


/**
 * getUrl
 */
if (!function_exists('http_get')) {
    function http_get($url, $headers = [])
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $tmpInfo = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;
    }
}

if (!function_exists('http_post')) {
    function http_post($url, $post_data = array(), $timeout = 30, $header = "", $data_type = "")
    {

        $header = empty($header) ? '' : $header;
        if ($data_type == 'json') {
            $post_string = json_encode($post_data);
        } elseif ($data_type == 'array') {
            $post_string = $post_data;
        } elseif (is_array($post_data)) {
            $post_string = http_build_query($post_data, '', '&');
        } else {
            $post_string = $post_data;
        }
        $ch = curl_init();    // 启动一个CURL会话
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url);     // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 对认证证书来源的检查   // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);     // Post提交的数据包
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);     // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        //curl_setopt($ch, CURLOPT_HEADER, true); // 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // 获取的信息以文件流的形式返回
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //模拟的header头

        $result = curl_exec($ch);


        // 打印请求的header信息
//        $a = curl_getinfo($ch);
//        return $a;
//        var_dump($a);
        curl_close($ch);
        return $result;
    }
}

//获取更目录
function getPath()
{
    $path = str_replace('\\', '/', ROOT_PATH);
    $path = explode('/', $path);
    $path = array_splice($path, 0, -2);
    $path = implode('/', $path);
    return $path . '/';
}


function sprintf_num($num = 0)
{
    return sprintf("%.2f", substr(sprintf("%.3f", $num), 0, -1));
}

//生成 '8C6526C3-AC14-4cc7-8DFE-924BD6173ADR'
function dmd5($str)
{
    $md5String = md5($str);

    $pattern = '/(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})/';
    $replacement = '$1-$2-$3-$4-$5';

    $splitString = preg_replace($pattern, $replacement, $md5String);
    $uppercaseString = strtoupper($splitString);

    return $uppercaseString;
}

//字幕文件时间轴转换
function srtTimeToSeconds($srtTime)
{
    $time = explode(',', $srtTime);
    list($hours, $minutes, $seconds) = explode(':', $time[0]);
    $total_seconds = $hours * 3600 + $minutes * 60 + $seconds;
    $total_time = $total_seconds * 1000 + (int)$time[1];
    return $total_time;
}

//判断是否是中文
function is_chinese($str)
{
    if (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $str)) { // 兼容gb2312,utf-8
        return true;
    }
    return false;
}

//获取根目录
function getRootPath()
{
    $root_path = explode('\\', ROOT_PATH);
    $root_path = array_splice($root_path, 0, -2);
    $root_path = implode('/', $root_path);
    return $root_path;
}

function scanFile($path)
{

    global $result;

    $files = scandir($path);

    foreach ($files as $file) {

        if ($file != '.' && $file != '..') {

            if (is_dir($path . '/' . $file)) {

                scanFile($path . '/' . $file);

            } else {

                $result[] = basename($file);

            }

        }

    }

    return $result;

}

function getSn()
{
    $return_array = array();
    // 获取当前电脑CPU序列号(每个CPU厂家都会分配一个唯一序列号),并存到数组中
    exec("wmic cpu get processorid", $return_array);
    // 取出CPU序列号
    $cpu_sn = $return_array[1];
    // 定义空数组
    $return_array = array();
    // 获取当前电脑主板序列号(每个电脑主板厂家都会分配一个唯一序列号),并存到数组中
    @exec("wmic baseboard get serialnumber", $return_array);
    // 取出电脑主板序列号
    $baseboard_sn = $return_array[1];
    // 去除字符串中的字符"-"
    $baseboard_sn = str_replace("-", "", $baseboard_sn);
    // 定义空数组
    $return_array = array();
    // 获取当前硬盘序列号(每个硬盘厂家都会分配一个唯一序列号),并存到数组中
    @exec("wmic diskdrive get SerialNumber", $return_array);
    $diskdrive_sn = $return_array[1];
    // md5加密
    $sn_str = md5($cpu_sn . $baseboard_sn . $diskdrive_sn);
    // 哈希ripemd160加密算法
    // 字符串再拼接上自定义字符串
    $sn = hash('ripemd160', $sn_str . 'teststr');
    return $sn;
}




