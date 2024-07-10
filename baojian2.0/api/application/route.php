<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
Route::rule('test','index/Test/index');//测试token

Route::rule('api/AiHuiHua/login','index/Login/login');
Route::rule('api/pc/config','index/SdImage/config');
Route::rule('api/pc/index','index/SdImage/index');

Route::rule('api/AiHuiHua/uploadImage','index/Index/uploadImage');//上传图片
Route::rule('api/AiHuiHua/uploadSrt','index/Index/uploadSrt');//上传字幕文件

//sd接口
Route::rule('api/AiHuiHua/getSdConfig','index/SdImage/getSdConfig');//获取sd配置
Route::rule('api/AiHuiHua/upscalers','index/SdImage/upscalers');//获取无损放大模型
Route::rule('api/AiHuiHua/sdImage','index/SdImage/sdImage');//文生图
Route::rule('api/AiHuiHua/imgToImg','index/SdImage/imgToImg');//图生图高清修复
Route::rule('api/AiHuiHua/upscalersImg','index/SdImage/upscalersImg');//无损

//百度接口 有道云接口 提取关键词 翻译
Route::rule('api/AiHuiHua/reasoning','index/Reasoning/reasoning');//推理

//剪映
Route::rule('api/JianYin/index','index/JianYin/index');//剪映到草稿箱

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
