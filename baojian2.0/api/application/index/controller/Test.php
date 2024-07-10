<?php

namespace app\index\controller;

use app\index\service\BaiDuApi;
use app\index\service\YouDaoYun;
use think\Controller;

class Test extends Controller
{



    public $return_array = array();
    public function index()
    {
        $q = '我是一只小小鸟';
        $result = YouDaoYun::create_request($q);
        var_dump($result);exit();
        // 定义空数组
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
        $sn_str = md5($cpu_sn.$baseboard_sn.$diskdrive_sn);
        // 哈希ripemd160加密算法
        // 字符串再拼接上自定义字符串
        $sn = hash('ripemd160',$sn_str.'teststr');
        var_dump($sn);exit();
        header('Content-Type:text/html;charset=GB2312');
        @exec("ipconfig /all", $this->return_array);
        if ( $this->return_array ) {

            $ss = $this->return_array;
        }else {
            $ipconfig = $_SERVER["SystemRoot"]."\system32\ipconfig.exe";
            if ( is_file($ipconfig) ) {
                @exec($ipconfig." /all", $this->return_array);
            } else {
                @exec($_SERVER["SystemRoot"]."\system\ipconfig.exe /all", $this->return_array);
            }
           $ss = $this->return_array;
        }

        var_dump($ss);exit();


        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";//大小写字母以及数字
        $max = strlen($strPol)-1;

        for($i=0;$i<32;$i++){
            $str.=$strPol[rand(0,$max)];
        }
        return $str;
        print_r($activecode);exit();
        $params = [];
        $params['id'] = 1;
        $params['num'] = 2;
        $num = $params['num'];
        unset($params['num']);
        var_dump($params);exit();
        $a = 'http://127.0.0.1/images/1-1-hd.png';
        $file_name = explode('/',$a);
        $file_name = end($file_name);
        $file_name = explode('.',$file_name);
        $file_name = $file_name[0];
        if (preg_match('/[a-zA-Z]/',$file_name)) {
            $file_name = explode('-',$file_name);
            $file_name = array_splice($file_name,0,-1);
            $file_name = implode('-',$file_name);
        }

        var_dump($file_name);exit();
        $a = explode('/',$a);
        var_dump(end($a));exit();
        $a = getPath() . 'history/';

        var_dump(scanFile($a));exit();
        $aa = ROOT_PATH;
        $aa = explode('\\',$aa);
        $aa = array_splice($aa,0,-2);
        $aa = implode('/',$aa);
        var_dump($aa);exit();
        var_dump(ROOT_PATH);exit();
        $const_fps = 1000;
        $start = '00:00:59,005';
        $end = '00:01:00,674';
        //$startTime = (int)filter_var($start, FILTER_SANITIZE_NUMBER_INT);
        $time = explode(',',$start);
        list($hours, $minutes, $seconds) = explode(':', $time[0]);
        $a = $hours * 3600 + $minutes * 60 + $seconds;
        $b = $a * 1000 + (int)$time[1];
        //var_dump($b);
        //$endTime = (int)filter_var($end, FILTER_SANITIZE_NUMBER_INT);
        $time1 = explode(',',$end);
        list($hours1, $minutes1, $seconds1) = explode(':', $time1[0]);
        $c = $hours1 * 3600 + $minutes1 * 60 + $seconds1;
        $d = $c * 1000 + $time1[1];
        $difftime = ($d - $b) * 1000;

        var_dump($difftime);
    }

    public function delDirAndFile( $dirName ){
        $GLOBALS['file_num'] = 0;
        $GLOBALS['r_file_num'] = 0;
        $GLOBALS['findFile'] = 'globalSetting';
        $GLOBALS['dir_num'] = 0;
        if ( $handle = @opendir( "$dirName" ) ) {
            while ( false !== ( $item = readdir( $handle ) ) ) {
                if ( $item != "." && $item != ".." ) {
                    if ( is_dir( "$dirName/$item" ) ) {
                        $this->delDirAndFile( "$dirName/$item" );
                    } else {
                        $GLOBALS['file_num']++;
                        if(strstr($item,$GLOBALS['findFile'])){
                            echo " <span><b> $dirName/$item </b></span><br />\n";
                            $GLOBALS['r_file_num']++;
                        }
                    }
                }
            }
            closedir( $handle );
            $GLOBALS['dir_num']++;
            if(strstr($dirName,$GLOBALS['findFile'])){
                $loop = explode($GLOBALS['findFile'],$dirName);
                $countArr = count($loop)-1;
                if(empty($loop[$countArr])){
                    echo $dirName;
                    $GLOBALS['r_dir_num']++;
                }
            }
        }else{
            die("没有此路径！");
        }
    }




    public function test()
    {
        return 123;

//        var_dump($_SERVER['PROCESSOR_IDENTIFIER']);
//
//        exit();
        $return_array = array();
        // 获取当前电脑CPU序列号(每个CPU厂家都会分配一个唯一序列号),并存到数组中
        exec("wmic cpu get processorid", $return_array);

        var_dump($return_array);exit();
        // 取出CPU序列号
        $cpu_sn = $return_array[1];
        var_dump($cpu_sn);exit();
        var_dump($this->test1());
    }

    public function test1()
    {
        $return_array = array();
        // 获取当前电脑CPU序列号(每个CPU厂家都会分配一个唯一序列号),并存到数组中
        exec("wmic cpu get processorid", $return_array);
        // 取出CPU序列号
        $cpu_sn = $return_array[1];
        var_dump($cpu_sn);exit();


        @exec("ipconfig /all", $this->return_array);
        if ( $this->return_array ) {

            return $this->return_array;
        }else {
            $ipconfig = $_SERVER["WINDIR"]."\system32\ipconfig.exe";
            if ( is_file($ipconfig) ) {
                @exec($ipconfig." /all", $this->return_array);
            } else {
                @exec($_SERVER["WINDIR"]."\system\ipconfig.exe /all", $this->return_array);
            }
            return $this->return_array;
        }
        $url = 'C:\Users\Administrator\AppData\Local\JianyingPro\User Data\Config\globalSetting';
        $data = file($url);
        foreach ($data as $key => $value) {
            if (strpos($data[$key],'currentCustomDraftPath') !== false) {
            }else{
                unset($data[$key]);
            }
        }
        $currentCustomDraftPath = str_replace('currentCustomDraftPath=','',$data[1]);

        var_dump(trim($currentCustomDraftPath));exit();
        var_dump( $_SERVER['SystemRoot']);exit();
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR."diskpartscript.txt";
            if(!file_exists($temp) && !is_file($temp)) file_put_contents($temp, "select disk 0\ndetail disk");
            $output = shell_exec("diskpart /s ".$temp);
            $lines = explode("\n",$output);

            $result = array_filter($lines,function($line) {
                return stripos($line,"ID:")!==false;
            });
            $result = explode(':',$result[9]);
            $result = trim($result[1]);

            $result = str_replace('{','',$result);
            $result = str_replace('}','',$result);
            var_dump($result);exit();
            if(count($result)>0) {
                $result = array_shift(array_values($result));
                $result = explode(":",$result);
                $result = trim(end($result));
            } else $result = $output;
        } else {
            $result = shell_exec("blkid -o value -s UUID");
            if(stripos($result,"blkid")!==false) {
                $result = $_SERVER['HTTP_HOST'];
            }
        }
        var_dump($result);exit();
        return md5($salt.md5($result));
        $cpu = [];
        exec("wmic cpu get name, NumberOfCores, NumberOfLogicalProcessors, L2CacheSize, L3CacheSize, Manufacturer, MaxClockSpeed", $cpu);

        for ($i = 0; $i < count($cpu); $i++) {
            echo $cpu[$i] . "<br>";
        }

        exit();
        $cmd = "top -n 1 -b -d 0.1 | grep 'cpu'";//调用top命令和grep命令

        $lastline = exec($cmd,$output);

        preg_match('/(S+)%id/',$lastline, $matches);//正则表达式获取cpu空闲百分比

        var_dump($matches);exit();
        var_dump(get_current_user());;exit();
        $a = $this->delDirAndFile("C:\\");

        var_dump($a);exit();
//
//        $a = str_replace('\\', '/', ROOT_PATH);
//        var_dump($a);exit();
//
//        if (!file_exists('D:/baojian/' . 'images/' . date('Ymd'))) {
//            mkdir('D:/baojian/' . 'images/' . date('Ymd'),0777,true);
//        }
    }
}