<?php

namespace app\index\controller;

use think\Controller;
use think\Log;
use think\Request;

class JianYin extends Controller
{

    public float $ov = 0.3;      //溢出值     图片最短边铺满后 多溢出的比例; 随机运动时的运动范围 （不出黑边）
    public $istx = false;      //是否开启随机特效
    public $size = 9;          //字幕大小  默认8
    public $weizhi = -0.7;      //字幕位置  画布 中心原点 Y轴向下 70%位置；


    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE, post');// 表示只允许POST请求
        header('Access-Control-Allow-Headers:*');
    }

    public function index()
    {
        $user_path = get_current_user();
        $jianyin_setting_path = 'C:/Users/' . $user_path . '/AppData/Local/JianyingPro/User Data/Config/globalSetting';
        $jianyin_setting_info = file($jianyin_setting_path);
        foreach ($jianyin_setting_info as $key => $value) {
            if (strpos($jianyin_setting_info[$key], 'currentCustomDraftPath') === false) {
                unset($jianyin_setting_info[$key]);
            }
        }

        $currentCustomDraftPath = str_replace('currentCustomDraftPath=', '', array_shift($jianyin_setting_info));//草稿箱位置
        $currentCustomDraftPath = trim($currentCustomDraftPath);
        $currentCustomDraftPath = str_replace('\\', '/', $currentCustomDraftPath);
        $uri = preg_replace('#//+#', '/', $currentCustomDraftPath);//草稿箱所在路径
        $srtList = Request::instance()->post('lists/a');
        if (is_array($srtList)) {
        }else{
            $data_info = [];
            $data_info['code'] = 0;
            $data_info['show'] = 1;
            $data_info['msg'] = '合成失败,';
            $data_info['data'] = [];
            return json($data_info);
        }

        $ext = date('YmdHis',time());
        $filename = getPath() . 'history/';
        $data_info = json_encode($srtList,JSON_UNESCAPED_UNICODE);
        $c_filename = $filename . 'ai-'. time() . '-' . $ext . '.json';
        $c_myfile = fopen($c_filename, "w") or die("Unable to open file!");
        fwrite($c_myfile, $data_info);
        fclose($c_myfile);



        $subStart = $srtList[0]['sub'][0]['start'];
        $const_fps = 1000;  //常量
        $subLast = $srtList[count($srtList) - 1]['sub'];
        $subLast = $subLast[count($subLast) - 1]['end'];
        $endTime = srtTimeToSeconds($subLast);
        $substartTime = srtTimeToSeconds($subStart);
        $diffEndTime = $endTime - $substartTime;//总持续时间
        $str_path = getPath() . 'images/';//本地图片路径

        $arr = [
            "config" => [
                "width" => 1920,            //视频画布宽
                "height" => 1440,            //视频画布高
                "fps" => 30.0,                //视频帧率   每秒刷新率
                'duration' => $diffEndTime * $const_fps, //总持续时间 秒*1000000
                'draft_root_path' => $uri, //草稿所在文件夹 完整本地路径
                //'draft_root_path' => 'D:/ProgramFiles/toolSoft/JianyingPro Drafts', //草稿所在文件夹 完整本地路径
                'draft_name' =>'ai_', //草稿名称
            ],
        ];
        $jianyin_prams_img = [];

        foreach ($srtList as $key => $item) {
            if (array_key_exists('img',$item)) {
                $imgInfo = explode('/', $item['img']['url']); //图片信息
                $subInfo = $srtList[$key]['sub'];  //字幕列表
                $endTime_info = srtTimeToSeconds(end($subInfo)['end']);//结算时间
                //取第一位数组
                $startTime_info = srtTimeToSeconds($subInfo[0]['start']);
                $diff_time= 0;
                if($key < count($srtList)-1){
                    $next_srt = next($srtList);
                    $next_srt = $next_srt['sub'][0]['start'];
                    $next_start = srtTimeToSeconds($next_srt);
                    $diff_time = $next_start - $endTime_info;
                }
                $duration = $endTime_info - $startTime_info + $diff_time;


//                $index_ent = count($subInfo) - 1;
//                //$endTime_info = (int)filter_var($subInfo[$index_ent]['end'], FILTER_SANITIZE_NUMBER_INT) * $const_fps; //结算时间
//                $endTime_info = srtTimeToSeconds($subInfo[$index_ent]['end']) * $const_fps; //结算时间
//                $index_ent_next = 0;
//                if($key < count($srtList)-1){
//                    $nextSub = $srtList[$key+1]['sub'];
//                    $index_next_start  = $nextSub[count($nextSub)-1]['start'];
//                    //$start_next_time = (int)filter_var($index_next_start, FILTER_SANITIZE_NUMBER_INT) * $const_fps ;
//                    $start_next_time = srtTimeToSeconds($index_next_start) * $const_fps ;
//                    $index_ent_next =  $start_next_time-$endTime_info;
//
//                    //$startTime_info = (int)filter_var($subInfo[$index_start]['start'], FILTER_SANITIZE_NUMBER_INT) * $const_fps ;//开始时间
//                    $startTime_info = srtTimeToSeconds($subInfo[$index_start]['start']) * $const_fps ;//开始时间
//                }
//                $duration = $endTime_info - $startTime_info + $index_ent_next;
                $imgUrl = str_replace('http://127.0.0.1:6891/', $str_path, $item['img']['url']);
                $jianyin_prams_img[$key]['name'] = $imgInfo[count($imgInfo) - 1];
                $jianyin_prams_img[$key]['path'] = $imgUrl;
                $jianyin_prams_img[$key]['width'] = $item['img']['width'];
                $jianyin_prams_img[$key]['height'] = $item['img']['height'];
                $jianyin_prams_img[$key]['duration'] = $duration * 1000;
                if ($item['Keyframes']) {
                    $jianyin_prams_img[$key]['key'] = $this->getOperation($item['Keyframes']);
                }else{
                    $operation = ['top', 'bottom', 'left', 'right', 'big', 'small'];
                    $ks = array_rand($operation, 1);//输出随机内容
                    $jianyin_prams_img[$key]['key'] = $operation[$ks];
                }

                $jianyin_prams_img[$key]['speed'] = $item['power'];
            }

        }


        $jianyin_prams_text = [];
        foreach ($srtList as $key => $item) {
            foreach ($item['sub'] as $k => $value) {
                $info['text'] = $value['text'];
                $startTime = srtTimeToSeconds($value['start']) * 1000;
                $endTime = srtTimeToSeconds($value['end']) * 1000;
                $info['duration'] = $endTime - $startTime;
                $info['start'] = $startTime;
                array_push($jianyin_prams_text, $info);

            }


        }
        $arr['imgs'] = $jianyin_prams_img;//照片
        $arr['texts'] = $jianyin_prams_text;//字幕
        $arr["audios"] = [];

        //return json($arr);

        if (empty($arr)) {
            $data_info = [];
            $data_info['code'] = 0;
            $data_info['show'] = 1;
            $data_info['msg'] = '合成失败,数据不能为空';
            $data_info['data'] = [];
            return json($data_info);
        }
        $array = array(
            'canvas_config' =>    //画布设置
                array(
                    'height' => (int)$arr["config"]["height"], //
                    'ratio' => '4:3', //比例  原始 original
                    'width' => (int)$arr["config"]["width"], //
                ),
            'color_space' => 0, //颜色空间
            'config' =>
                array(
                    'adjust_max_index' => 1,
                    'attachment_info' =>
                        array(),
                    'combination_max_index' => 1,
                    'export_range' => NULL,
                    'extract_audio_last_index' => 1,
                    'lyrics_recognition_id' => '',
                    'lyrics_sync' => true,
                    'lyrics_taskinfo' =>
                        array(),
                    'maintrack_adsorb' => true,
                    'material_save_mode' => 0,
                    'original_sound_last_index' => 1,
                    'record_audio_last_index' => 1,
                    'sticker_max_index' => 1,
                    'subtitle_recognition_id' => '',
                    'subtitle_sync' => true,
                    'subtitle_taskinfo' =>
                        array(),
                    'system_font_list' =>
                        array(),
                    'video_mute' => false,
                    'zoom_info_params' => NULL,
                ),
            'cover' => NULL,
            'create_time' => 0,
            'duration' => (int)$arr["config"]["duration"], //持续时间 //15000000
            'extra_info' => NULL,
            'fps' => $arr["config"]["fps"], //30.0
            'free_render_index_mode_on' => false,
            'group_container' => NULL,  //组容器
            'id' => '00C4FBC0-02E0-4502-9E0C-1241EC91B3BA',
            'keyframe_graph_list' =>   //关键帧图表列表
                array(),
            'keyframes' =>   //关键帧
                array(
                    'adjusts' =>
                        array(),
                    'audios' =>
                        array(),
                    'effects' =>
                        array(),
                    'filters' =>
                        array(),
                    'handwrites' =>
                        array(),
                    'stickers' =>
                        array(),
                    'texts' =>
                        array(),
                    'videos' =>
                        array(),
                ),
            'last_modified_platform' => //最后一次进行修改的具体平台
                array(
                    'app_id' => 3704,
                    'app_source' => 'lv',
                    'app_version' => '4.2.0',
                    'device_id' => '75644bdee73eee451eeaed3b0f8193ab',
                    'hard_disk_id' => 'd74be8841ea0811ef6ce20d55fdaaeb9',
                    'mac_address' => '31f2e59a3d2f5e54806f838d133b8aa4',
                    'os' => 'windows',
                    'os_version' => '10.0.22000',
                ),
            'materials' =>        //素材
                array(
                    'audio_balances' =>
                        array(),
                    'audio_effects' =>
                        array(),
                    'audio_fades' =>
                        array(),
                    'audios' =>
                        array(),
                    'beats' =>
                        array(),
                    'canvases' => //画布
                        array(
                            0 =>
                                array(
                                    'album_image' => '', //相册图片
                                    'blur' => 0.0,     //模糊
                                    'color' => '',
                                    'id' => 'D7172D30-FD72-4a3b-9C9B-8965DFD76F50',
                                    'image' => '',
                                    'image_id' => '',
                                    'image_name' => '',
                                    'source_platform' => 0, //源平台
                                    'team_id' => '',
                                    'type' => 'canvas_color',
                                ),
                            1 =>
                                array(
                                    'album_image' => '',
                                    'blur' => 0.0,
                                    'color' => '',
                                    'id' => '45BAA080-56D0-48f7-A475-C2FF291FE106',
                                    'image' => '',
                                    'image_id' => '',
                                    'image_name' => '',
                                    'source_platform' => 0,
                                    'team_id' => '',
                                    'type' => 'canvas_color',
                                ),
                            2 =>
                                array(
                                    'album_image' => '',
                                    'blur' => 0.0,
                                    'color' => '',
                                    'id' => '7F7F2680-A137-41ea-AF11-C7D9DC5A0472',
                                    'image' => '',
                                    'image_id' => '',
                                    'image_name' => '',
                                    'source_platform' => 0,
                                    'team_id' => '',
                                    'type' => 'canvas_color',
                                ),
                            3 =>
                                array(
                                    'album_image' => '',
                                    'blur' => 0.0,
                                    'color' => '',
                                    'id' => '8289ED99-BB20-49d7-B509-BA8A34A809D2',
                                    'image' => '',
                                    'image_id' => '',
                                    'image_name' => '',
                                    'source_platform' => 0,
                                    'team_id' => '',
                                    'type' => 'canvas_color',
                                ),
                        ),
                    'chromas' =>
                        array(),
                    'color_curves' =>
                        array(),
                    'drafts' =>
                        array(),
                    'effects' =>
                        array(),
                    'green_screens' =>
                        array(),
                    'handwrites' =>
                        array(),
                    'hsl' =>
                        array(),
                    'images' =>
                        array(),
                    'log_color_wheels' =>
                        array(),
                    'manual_deformations' =>
                        array(),
                    'masks' =>
                        array(),
                    'material_animations' => //材质动画
                        array(
                            0 =>
                                array(
                                    'animations' =>
                                        array(),
                                    'id' => '3C818FDB-86A1-4334-AFF3-A12AA565D34B',
                                    'type' => 'sticker_animation',
                                ),
                            1 =>
                                array(
                                    'animations' =>
                                        array(),
                                    'id' => '10443C28-7A4E-40df-8712-E8269E517974',
                                    'type' => 'sticker_animation',
                                ),
                            2 =>
                                array(
                                    'animations' =>
                                        array(),
                                    'id' => '15B7DF55-BC5A-43a7-8654-0A49FB3761A3',
                                    'type' => 'sticker_animation',
                                ),
                            3 =>
                                array(
                                    'animations' =>
                                        array(),
                                    'id' => 'B2DD17F8-3C4D-4f1c-8917-FFAE705834B8',
                                    'type' => 'sticker_animation',
                                ),
                            4 =>
                                array(
                                    'animations' =>
                                        array(),
                                    'id' => '2AC00497-20F1-4301-957E-713C4C22B19F',
                                    'type' => 'sticker_animation',
                                ),
                            5 =>
                                array(
                                    'animations' =>
                                        array(),
                                    'id' => 'C4B57D9B-9649-44b9-867F-443861FD4C78',
                                    'type' => 'sticker_animation',
                                ),
                        ),
                    'placeholders' =>
                        array(),
                    'plugin_effects' =>
                        array(),
                    'primary_color_wheels' =>
                        array(),
                    'realtime_denoises' =>
                        array(),
                    'sound_channel_mappings' => //声音通道映射
                        array(
                            0 =>
                                array(
                                    'audio_channel_mapping' => 0,
                                    'id' => 'BC79DDA4-96D5-4c79-AD72-DC9A33FFDF8D',
                                    'is_config_open' => false,
                                    'type' => '',
                                ),
                            1 =>
                                array(
                                    'audio_channel_mapping' => 0, //音频通道映射
                                    'id' => 'D33F5375-F800-4636-9EE1-71364C19D248',
                                    'is_config_open' => false,
                                    'type' => '',
                                ),
                            2 =>
                                array(
                                    'audio_channel_mapping' => 0,
                                    'id' => '9F712746-198B-44e0-A0D5-1A3E6AF6B537',
                                    'is_config_open' => false,
                                    'type' => '',
                                ),
                            3 =>
                                array(
                                    'audio_channel_mapping' => 0,
                                    'id' => '08012241-F550-46fd-B60E-2E2A893B32CA',
                                    'is_config_open' => false,
                                    'type' => '',
                                ),
                        ),
                    'speeds' =>  //播放速度
                        array(
                            0 =>
                                array(
                                    'curve_speed' => NULL,
                                    'id' => 'DCCF585E-AD3A-4f1b-BDC6-0B7188A9F5D3',
                                    'mode' => 0,
                                    'speed' => 1.0,
                                    'type' => 'speed',
                                ),
                            1 =>
                                array(
                                    'curve_speed' => NULL,
                                    'id' => 'AF11AEFB-C5ED-4917-92C5-437E8B049552',
                                    'mode' => 0,
                                    'speed' => 1.0,
                                    'type' => 'speed',
                                ),
                            2 =>
                                array(
                                    'curve_speed' => NULL,
                                    'id' => '86B51E0F-E278-4807-9AD1-54EB80D94DDC',
                                    'mode' => 0,
                                    'speed' => 1.0,
                                    'type' => 'speed',
                                ),
                            3 =>
                                array(
                                    'curve_speed' => NULL,
                                    'id' => '6639EE1F-167E-4c7c-B23E-CBB64E4F7FAC',
                                    'mode' => 0,
                                    'speed' => 1.0,
                                    'type' => 'speed',
                                ),
                        ),
                    'stickers' =>
                        array(),
                    'tail_leaders' =>
                        array(),
                    'text_templates' =>
                        array(),
                    'texts' =>   ///文本
                        array(
                            0 =>
                                array(
                                    'add_type' => 0,
                                    'alignment' => 1,
                                    'background_alpha' => 1.0,
                                    'background_color' => '',
                                    'background_height' => 1.0,
                                    'background_horizontal_offset' => 0.0,
                                    'background_round_radius' => 0.0,
                                    'background_style' => 0,
                                    'background_vertical_offset' => 0.0,
                                    'background_width' => 1.0,
                                    'bold_width' => 0.0,
                                    'border_color' => '#000000',
                                    'border_width' => 0.08,
                                    'check_flag' => 15,
                                    'content' => '<outline color=(0,0,0,1) width=0.08><size=15><color=(1,0.87059,0,1)><font id="6740435892441190919" path="C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/新青年体.ttf">[我是第一个文本]</font></color></size></outline>',
                                    'font_category_id' => '',
                                    'font_category_name' => '',
                                    'font_id' => '',
                                    'font_name' => '',
                                    'font_path' => 'C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/新青年体.ttf',
                                    'font_resource_id' => '6740435892441190919',
                                    'font_size' => 15.0,
                                    'font_source_platform' => 0,
                                    'font_team_id' => '',
                                    'font_title' => 'none',
                                    'font_url' => '',
                                    'fonts' =>
                                        array(
                                            0 =>
                                                array(
                                                    'category_id' => '',
                                                    'category_name' => '',
                                                    'effect_id' => '6740435892441190919',
                                                    'id' => 'A7D697F3-DD61-44bd-B2E0-38EEAE6C6227',
                                                    'path' => 'C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/新青年体.ttf',
                                                    'resource_id' => '6740435892441190919',
                                                    'source_platform' => 0,
                                                    'team_id' => '',
                                                    'title' => '新青年体',
                                                ),
                                        ),
                                    'force_apply_line_max_width' => false,
                                    'global_alpha' => 1.0,
                                    'group_id' => '',
                                    'has_shadow' => false,
                                    'id' => 'CE9C004D-93DF-4445-8320-BF4BEA9556B5',
                                    'initial_scale' => 1.0,
                                    'is_rich_text' => false,
                                    'italic_degree' => 0,
                                    'ktv_color' => '',
                                    'language' => '',
                                    'layer_weight' => 1,
                                    'letter_spacing' => 0.0,
                                    'line_spacing' => 0.02,
                                    'name' => '',
                                    'preset_category' => '',
                                    'preset_category_id' => '',
                                    'preset_has_set_alignment' => false,
                                    'preset_id' => '',
                                    'preset_index' => 0,
                                    'preset_name' => '',
                                    'recognize_type' => 0,
                                    'relevance_segment' =>
                                        array(),
                                    'shadow_alpha' => 0.0,
                                    'shadow_angle' => -45.0,
                                    'shadow_color' => '#000000',
                                    'shadow_distance' => 8.0,
                                    'shadow_point' =>
                                        array(
                                            'x' => 1.0182337649086284,
                                            'y' => -1.0182337649086284,
                                        ),
                                    'shadow_smoothing' => 0.99,
                                    'shape_clip_x' => false,
                                    'shape_clip_y' => false,
                                    'style_name' => '黄字黑边',
                                    'sub_type' => 0,
                                    'text_alpha' => 1.0,
                                    'text_color' => '#ffde00',
                                    'text_preset_resource_id' => '',
                                    'text_size' => 30,
                                    'text_to_audio_ids' =>
                                        array(),
                                    'tts_auto_update' => false,
                                    'type' => 'text',
                                    'typesetting' => 0,
                                    'underline' => false,
                                    'underline_offset' => 0.22,
                                    'underline_width' => 0.05,
                                    'use_effect_default_color' => false,
                                    'words' =>
                                        array(),
                                ),
                            1 =>
                                array(
                                    'add_type' => 0,
                                    'alignment' => 1,
                                    'background_alpha' => 1.0,
                                    'background_color' => '',
                                    'background_height' => 1.0,
                                    'background_horizontal_offset' => 0.0,
                                    'background_round_radius' => 0.0,
                                    'background_style' => 0,
                                    'background_vertical_offset' => 0.0,
                                    'background_width' => 1.0,
                                    'bold_width' => 0.0,
                                    'border_color' => '',
                                    'border_width' => 0.08,
                                    'check_flag' => 7,
                                    'content' => '<size=15><font id="" path="C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/SystemFont/zh-hans.ttf">[我是第二个文本]</font></size>',
                                    'font_category_id' => '',
                                    'font_category_name' => '',
                                    'font_id' => '',
                                    'font_name' => '',
                                    'font_path' => 'C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/SystemFont/zh-hans.ttf',
                                    'font_resource_id' => '',
                                    'font_size' => 15.0,
                                    'font_source_platform' => 0,
                                    'font_team_id' => '',
                                    'font_title' => 'none',
                                    'font_url' => '',
                                    'fonts' =>
                                        array(),
                                    'force_apply_line_max_width' => false,
                                    'global_alpha' => 1.0,
                                    'group_id' => '',
                                    'has_shadow' => false,
                                    'id' => '120E49B1-A52D-4dbd-95BA-E59E7222EFAC',
                                    'initial_scale' => 1.0,
                                    'is_rich_text' => false,
                                    'italic_degree' => 0,
                                    'ktv_color' => '',
                                    'language' => '',
                                    'layer_weight' => 1,
                                    'letter_spacing' => 0.0,
                                    'line_spacing' => 0.02,
                                    'name' => '',
                                    'preset_category' => '',
                                    'preset_category_id' => '',
                                    'preset_has_set_alignment' => false,
                                    'preset_id' => '',
                                    'preset_index' => 0,
                                    'preset_name' => '',
                                    'recognize_type' => 0,
                                    'relevance_segment' =>
                                        array(),
                                    'shadow_alpha' => 0.8,
                                    'shadow_angle' => -45.0,
                                    'shadow_color' => '',
                                    'shadow_distance' => 8.0,
                                    'shadow_point' =>
                                        array(
                                            'x' => 1.0182337649086284,
                                            'y' => -1.0182337649086284,
                                        ),
                                    'shadow_smoothing' => 1.0,
                                    'shape_clip_x' => false,
                                    'shape_clip_y' => false,
                                    'style_name' => '',
                                    'sub_type' => 0,
                                    'text_alpha' => 1.0,
                                    'text_color' => '#FFFFFF',
                                    'text_preset_resource_id' => '',
                                    'text_size' => 30,
                                    'text_to_audio_ids' =>
                                        array(),
                                    'tts_auto_update' => false,
                                    'type' => 'text',
                                    'typesetting' => 0,
                                    'underline' => false,
                                    'underline_offset' => 0.22,
                                    'underline_width' => 0.05,
                                    'use_effect_default_color' => true,
                                    'words' =>
                                        array(),
                                ),
                        ),
                    'transitions' =>
                        array(),
                    'video_effects' =>
                        array(
                            0 =>
                                array(
                                    'adjust_params' =>
                                        array(
                                            0 =>
                                                array(
                                                    'default_value' => 1.0,
                                                    'name' => 'effects_adjust_filter',
                                                    'value' => 1.0,
                                                ),
                                            1 =>
                                                array(
                                                    'default_value' => 1.0,
                                                    'name' => 'effects_adjust_background_animation',
                                                    'value' => 1.0,
                                                ),
                                            2 =>
                                                array(
                                                    'default_value' => 0.33,
                                                    'name' => 'effects_adjust_speed',
                                                    'value' => 0.33,
                                                ),
                                        ),
                                    'apply_target_type' => 0,
                                    'apply_time_range' => NULL,
                                    'category_id' => '7729',
                                    'category_name' => '氛围',
                                    'common_keyframes' =>
                                        array(),
                                    'effect_id' => '634175',
                                    'formula_id' => '',
                                    'id' => '07C7682E-DBA7-4719-87C2-F1A1BCE304A8',
                                    'name' => '夜蝶',
                                    'path' => 'C:/Users/shiyi/AppData/Local/JianyingPro/User Data/Cache/effect/634175/8a1a97c0b237adfb31c8ae55faa64e74',
                                    'platform' => 'all',
                                    'render_index' => 11000,
                                    'request_id' => '',
                                    'resource_id' => '6748376019524129292',
                                    'source_platform' => 0,
                                    'time_range' => NULL,
                                    'track_render_index' => 0,
                                    'type' => 'video_effect',
                                    'value' => 1.0,
                                    'version' => '',
                                ),
                            1 =>
                                array(
                                    'adjust_params' =>
                                        array(
                                            0 =>
                                                array(
                                                    'default_value' => 1.0,
                                                    'name' => 'effects_adjust_background_animation',
                                                    'value' => 1.0,
                                                ),
                                            1 =>
                                                array(
                                                    'default_value' => 0.33,
                                                    'name' => 'effects_adjust_speed',
                                                    'value' => 0.33,
                                                ),
                                        ),
                                    'apply_target_type' => 0,
                                    'apply_time_range' => NULL,
                                    'category_id' => '7734',
                                    'category_name' => '自然',
                                    'common_keyframes' =>
                                        array(),
                                    'effect_id' => '635069',
                                    'formula_id' => '',
                                    'id' => '1903EF86-ECBA-4f52-99C3-AED44525A142',
                                    'name' => '闪电',
                                    'path' => 'C:/Users/shiyi/AppData/Local/JianyingPro/User Data/Cache/effect/635069/6191fcf13bfd15e4288a81a174ba0ed8',
                                    'platform' => 'all',
                                    'render_index' => 11000,
                                    'request_id' => '',
                                    'resource_id' => '6734215409513271820',
                                    'source_platform' => 0,
                                    'time_range' => NULL,
                                    'track_render_index' => 0,
                                    'type' => 'video_effect',
                                    'value' => 1.0,
                                    'version' => '',
                                ),
                        ),
                    'video_trackings' =>
                        array(),
                    'videos' =>   //视频 图片
                        array(
                            0 =>
                                array(
                                    'audio_fade' => NULL,
                                    'cartoon_path' => '',
                                    'category_id' => '',
                                    'category_name' => '',
                                    'check_flag' => 63487,
                                    'crop' =>
                                        array(
                                            'lower_left_x' => 0.0,
                                            'lower_left_y' => 1.0,
                                            'lower_right_x' => 1.0,
                                            'lower_right_y' => 1.0,
                                            'upper_left_x' => 0.0,
                                            'upper_left_y' => 0.0,
                                            'upper_right_x' => 1.0,
                                            'upper_right_y' => 0.0,
                                        ),
                                    'crop_ratio' => 'free',
                                    'crop_scale' => 1.0,
                                    'duration' => 1049533333,
                                    'extra_type_option' => 0,
                                    'formula_id' => '',
                                    'freeze' => NULL,
                                    'gameplay' => NULL,
                                    'has_audio' => true,
                                    'height' => 1080,
                                    'id' => 'CE07E9AE-EBFC-400d-B081-61035B1C2317',
                                    'intensifies_audio_path' => '',
                                    'intensifies_path' => '',
                                    'is_unified_beauty_mode' => false,
                                    'local_id' => '',
                                    'local_material_id' => '',
                                    'material_id' => '',
                                    'material_name' => '恋综字幕.mp4',
                                    'material_url' => '',
                                    'matting' =>
                                        array(
                                            'flag' => 0,
                                            'has_use_quick_brush' => false,
                                            'has_use_quick_eraser' => false,
                                            'interactiveTime' =>
                                                array(),
                                            'path' => '',
                                            'strokes' =>
                                                array(),
                                        ),
                                    'media_path' => '',
                                    'object_locked' => NULL,
                                    'path' => 'C:/Users/shiyi/Desktop/ceshi/抖音/番茄畅听/恋综/恋综字幕.mp4',
                                    'picture_from' => 'none',
                                    'picture_set_category_id' => '',
                                    'picture_set_category_name' => '',
                                    'request_id' => '',
                                    'reverse_intensifies_path' => '',
                                    'reverse_path' => '',
                                    'source_platform' => 0,
                                    'stable' => NULL,
                                    'team_id' => '',
                                    'type' => 'video',
                                    'video_algorithm' =>
                                        array(
                                            'algorithms' =>
                                                array(),
                                            'deflicker' => NULL,
                                            'motion_blur_config' => NULL,
                                            'noise_reduction' => NULL,
                                            'path' => '',
                                            'time_range' => NULL,
                                        ),
                                    'width' => 1440,
                                ),
                            1 =>
                                array(
                                    'audio_fade' => NULL,
                                    'cartoon_path' => '',
                                    'category_id' => '',
                                    'category_name' => '',
                                    'check_flag' => 63487,
                                    'crop' =>
                                        array(
                                            'lower_left_x' => 0.0,
                                            'lower_left_y' => 1.0,
                                            'lower_right_x' => 1.0,
                                            'lower_right_y' => 1.0,
                                            'upper_left_x' => 0.0,
                                            'upper_left_y' => 0.0,
                                            'upper_right_x' => 1.0,
                                            'upper_right_y' => 0.0,
                                        ),
                                    'crop_ratio' => 'free',
                                    'crop_scale' => 1.0,
                                    'duration' => 10800000000,
                                    'extra_type_option' => 0,
                                    'formula_id' => '',
                                    'freeze' => NULL,
                                    'gameplay' => NULL,
                                    'has_audio' => false,
                                    'height' => 998,
                                    'id' => '6481C2B4-DC0C-41b9-9778-D7DB7390F181',
                                    'intensifies_audio_path' => '',
                                    'intensifies_path' => '',
                                    'is_unified_beauty_mode' => false,
                                    'local_id' => '',
                                    'local_material_id' => '',
                                    'material_id' => '',
                                    'material_name' => '1.jpg',
                                    'material_url' => '',
                                    'matting' =>
                                        array(
                                            'flag' => 0,
                                            'has_use_quick_brush' => false,
                                            'has_use_quick_eraser' => false,
                                            'interactiveTime' =>
                                                array(),
                                            'path' => '',
                                            'strokes' =>
                                                array(),
                                        ),
                                    'media_path' => '',
                                    'object_locked' => NULL,
                                    'path' => 'C:/Users/shiyi/Desktop/ceshi/1.jpg',
                                    'picture_from' => 'none',
                                    'picture_set_category_id' => '',
                                    'picture_set_category_name' => '',
                                    'request_id' => '',
                                    'reverse_intensifies_path' => '',
                                    'reverse_path' => '',
                                    'source_platform' => 0,
                                    'stable' => NULL,
                                    'team_id' => '',
                                    'type' => 'photo',
                                    'video_algorithm' =>
                                        array(
                                            'algorithms' =>
                                                array(),
                                            'deflicker' => NULL,
                                            'motion_blur_config' => NULL,
                                            'noise_reduction' => NULL,
                                            'path' => '',
                                            'time_range' => NULL,
                                        ),
                                    'width' => 448,
                                ),
                            2 =>
                                array(
                                    'audio_fade' => NULL,
                                    'cartoon_path' => '',
                                    'category_id' => '',
                                    'category_name' => '',
                                    'check_flag' => 63487,
                                    'crop' =>
                                        array(
                                            'lower_left_x' => 0.0,
                                            'lower_left_y' => 1.0,
                                            'lower_right_x' => 1.0,
                                            'lower_right_y' => 1.0,
                                            'upper_left_x' => 0.0,
                                            'upper_left_y' => 0.0,
                                            'upper_right_x' => 1.0,
                                            'upper_right_y' => 0.0,
                                        ),
                                    'crop_ratio' => 'free',
                                    'crop_scale' => 1.0,
                                    'duration' => 10800000000,
                                    'extra_type_option' => 0,
                                    'formula_id' => '',
                                    'freeze' => NULL,
                                    'gameplay' => NULL,
                                    'has_audio' => false,
                                    'height' => 998,
                                    'id' => '3963376E-4C2A-4fde-8A0C-94FC7F72398F',
                                    'intensifies_audio_path' => '',
                                    'intensifies_path' => '',
                                    'is_unified_beauty_mode' => false,
                                    'local_id' => '',
                                    'local_material_id' => '',
                                    'material_id' => '',
                                    'material_name' => '2.jpg',
                                    'material_url' => '',
                                    'matting' =>
                                        array(
                                            'flag' => 0,
                                            'has_use_quick_brush' => false,
                                            'has_use_quick_eraser' => false,
                                            'interactiveTime' =>
                                                array(),
                                            'path' => '',
                                            'strokes' =>
                                                array(),
                                        ),
                                    'media_path' => '',
                                    'object_locked' => NULL,
                                    'path' => 'C:/Users/shiyi/Desktop/ceshi/2.jpg',
                                    'picture_from' => 'none',
                                    'picture_set_category_id' => '',
                                    'picture_set_category_name' => '',
                                    'request_id' => '',
                                    'reverse_intensifies_path' => '',
                                    'reverse_path' => '',
                                    'source_platform' => 0,
                                    'stable' => NULL,
                                    'team_id' => '',
                                    'type' => 'photo',
                                    'video_algorithm' =>
                                        array(
                                            'algorithms' =>
                                                array(),
                                            'deflicker' => NULL,
                                            'motion_blur_config' => NULL,
                                            'noise_reduction' => NULL,
                                            'path' => '',
                                            'time_range' => NULL,
                                        ),
                                    'width' => 448,
                                ),
                            3 =>
                                array(
                                    'audio_fade' => NULL,
                                    'cartoon_path' => '',
                                    'category_id' => '',
                                    'category_name' => 'local',
                                    'check_flag' => 63487,
                                    'crop' =>
                                        array(
                                            'lower_left_x' => 0.0,
                                            'lower_left_y' => 1.0,
                                            'lower_right_x' => 1.0,
                                            'lower_right_y' => 1.0,
                                            'upper_left_x' => 0.0,
                                            'upper_left_y' => 0.0,
                                            'upper_right_x' => 1.0,
                                            'upper_right_y' => 0.0,
                                        ),
                                    'crop_ratio' => 'free',
                                    'crop_scale' => 1.0,
                                    'duration' => 10800000000,
                                    'extra_type_option' => 0,
                                    'formula_id' => '',
                                    'freeze' => NULL,
                                    'gameplay' => NULL,
                                    'has_audio' => false,
                                    'height' => 1360,
                                    'id' => 'C61173C8-7F8E-4a5b-B4FA-23B2C4DD9A0B',
                                    'intensifies_audio_path' => '',
                                    'intensifies_path' => '',
                                    'is_unified_beauty_mode' => false,
                                    'local_id' => '',
                                    'local_material_id' => '',
                                    'material_id' => '',
                                    'material_name' => '3.png',
                                    'material_url' => '',
                                    'matting' =>
                                        array(
                                            'flag' => 0,
                                            'has_use_quick_brush' => false,
                                            'has_use_quick_eraser' => false,
                                            'interactiveTime' =>
                                                array(),
                                            'path' => '',
                                            'strokes' =>
                                                array(),
                                        ),
                                    'media_path' => '',
                                    'object_locked' => NULL,
                                    'path' => 'C:/Users/shiyi/Desktop/ceshi/3.png',
                                    'picture_from' => 'none',
                                    'picture_set_category_id' => '',
                                    'picture_set_category_name' => '',
                                    'request_id' => '',
                                    'reverse_intensifies_path' => '',
                                    'reverse_path' => '',
                                    'source_platform' => 0,
                                    'stable' => NULL,
                                    'team_id' => '',
                                    'type' => 'photo',
                                    'video_algorithm' =>
                                        array(
                                            'algorithms' =>
                                                array(),
                                            'deflicker' => NULL,
                                            'motion_blur_config' => NULL,
                                            'noise_reduction' => NULL,
                                            'path' => '',
                                            'time_range' => NULL,
                                        ),
                                    'width' => 1024,
                                ),
                        ),
                ),
            'mutable_config' => NULL,
            'name' => '',
            'new_version' => '75.0.0',
            'platform' =>
                array(
                    'app_id' => 3704,
                    'app_source' => 'lv',
                    'app_version' => '4.2.0',
                    'device_id' => '75644bdee73eee451eeaed3b0f8193ab',
                    'hard_disk_id' => 'd74be8841ea0811ef6ce20d55fdaaeb9',
                    'mac_address' => '31f2e59a3d2f5e54806f838d133b8aa4',
                    'os' => 'windows',
                    'os_version' => '10.0.22000',
                ),
            'relationships' =>
                array(),
            'render_index_track_mode_on' => false,
            'retouch_cover' => NULL,
            'source' => 'default',
            'static_cover_image_path' => '',
            'tracks' =>                 //轨道
                array(
                    0 =>
                        array(
                            'attribute' => 0,
                            'flag' => 0,
                            'id' => '1309C52E-BDB5-456c-8791-8C7F36AD956E',
                            'segments' =>        //片段
                                array(
                                    0 =>
                                        array(
                                            'cartoon' => false,
                                            'clip' =>
                                                array(
                                                    'alpha' => 1.0,
                                                    'flip' =>
                                                        array(
                                                            'horizontal' => false,
                                                            'vertical' => false,
                                                        ),
                                                    'rotation' => 0.0,
                                                    'scale' =>
                                                        array(
                                                            'x' => 1.0,
                                                            'y' => 1.0,
                                                        ),
                                                    'transform' =>
                                                        array(
                                                            'x' => 0.0,
                                                            'y' => 0.0,
                                                        ),
                                                ),
                                            'common_keyframes' =>
                                                array(),
                                            'enable_adjust' => true,
                                            'enable_color_curves' => true,
                                            'enable_color_wheels' => true,
                                            'enable_lut' => true,
                                            'enable_smart_color_adjust' => false,
                                            'extra_material_refs' =>
                                                array(
                                                    0 => 'DCCF585E-AD3A-4f1b-BDC6-0B7188A9F5D3',   //速度 0
                                                    1 => 'D7172D30-FD72-4a3b-9C9B-8965DFD76F50',   //画布 0
                                                    2 => '3C818FDB-86A1-4334-AFF3-A12AA565D34B',   //材质 0
                                                    3 => 'BC79DDA4-96D5-4c79-AD72-DC9A33FFDF8D',   //声道 0
                                                ),
                                            'group_id' => '',
                                            'hdr_settings' =>
                                                array(
                                                    'intensity' => 1.0,
                                                    'mode' => 1,
                                                    'nits' => 1000,
                                                ),
                                            'id' => '6832FE4B-9DD4-4ec5-9F95-A1454F2F8CFC',
                                            'intensifies_audio' => false,
                                            'is_placeholder' => false,
                                            'is_tone_modify' => false,
                                            'keyframe_refs' =>
                                                array(),
                                            'last_nonzero_volume' => 1.0,
                                            'material_id' => 'CE07E9AE-EBFC-400d-B081-61035B1C2317',   //mp4 素材
                                            'render_index' => 0,
                                            'reverse' => false,
                                            'source_timerange' =>  //时间段截取
                                                array(
                                                    'duration' => 15000000,   //15秒
                                                    'start' => 0,
                                                ),
                                            'speed' => 1.0,
                                            'target_timerange' =>
                                                array(
                                                    'duration' => 15000000,
                                                    'start' => 0,
                                                ),
                                            'template_id' => '',
                                            'template_scene' => 'default',
                                            'track_attribute' => 0,
                                            'track_render_index' => 0,
                                            'visible' => true,
                                            'volume' => 1.0,
                                        ),
                                ),
                            'type' => 'video',
                        ),
                    1 =>
                        array(
                            'attribute' => 0,
                            'flag' => 2,
                            'id' => '8C6526C3-AC14-4cc7-8DFE-924BD6173AFD',
                            'segments' =>
                                array(
                                    0 =>
                                        array(
                                            'cartoon' => false,
                                            'clip' =>
                                                array(
                                                    'alpha' => 1.0,
                                                    'flip' =>
                                                        array(
                                                            'horizontal' => false,
                                                            'vertical' => false,
                                                        ),
                                                    'rotation' => 0.0,
                                                    'scale' =>
                                                        array(
                                                            'x' => 1.0,
                                                            'y' => 1.0,
                                                        ),
                                                    'transform' =>
                                                        array(
                                                            'x' => 0.0,
                                                            'y' => 0.0,
                                                        ),
                                                ),
                                            'common_keyframes' =>
                                                array(),
                                            'enable_adjust' => true,
                                            'enable_color_curves' => true,
                                            'enable_color_wheels' => true,
                                            'enable_lut' => true,
                                            'enable_smart_color_adjust' => false,
                                            'extra_material_refs' =>
                                                array(
                                                    0 => 'AF11AEFB-C5ED-4917-92C5-437E8B049552',
                                                    1 => '45BAA080-56D0-48f7-A475-C2FF291FE106',
                                                    2 => '10443C28-7A4E-40df-8712-E8269E517974',
                                                    3 => 'D33F5375-F800-4636-9EE1-71364C19D248',
                                                ),
                                            'group_id' => '',
                                            'hdr_settings' =>
                                                array(
                                                    'intensity' => 1.0,
                                                    'mode' => 1,
                                                    'nits' => 1000,
                                                ),
                                            'id' => 'BBCF1C2D-DE3A-41b3-A83B-A045BB9A5290',
                                            'intensifies_audio' => false,
                                            'is_placeholder' => false,
                                            'is_tone_modify' => false,
                                            'keyframe_refs' =>
                                                array(),
                                            'last_nonzero_volume' => 1.0,
                                            'material_id' => '6481C2B4-DC0C-41b9-9778-D7DB7390F181',  //1.jpg
                                            'render_index' => 1,
                                            'reverse' => false,
                                            'source_timerange' =>
                                                array(
                                                    'duration' => 5000000,  //5秒
                                                    'start' => 0,
                                                ),
                                            'speed' => 1.0,
                                            'target_timerange' =>
                                                array(
                                                    'duration' => 5000000,
                                                    'start' => 0,
                                                ),
                                            'template_id' => '',
                                            'template_scene' => 'default',
                                            'track_attribute' => 0,
                                            'track_render_index' => 0,
                                            'visible' => true,
                                            'volume' => 1.0,
                                        ),
                                    1 =>
                                        array(
                                            'cartoon' => false,
                                            'clip' =>
                                                array(
                                                    'alpha' => 1.0,
                                                    'flip' =>
                                                        array(
                                                            'horizontal' => false,
                                                            'vertical' => false,
                                                        ),
                                                    'rotation' => 0.0,
                                                    'scale' =>
                                                        array(
                                                            'x' => 2.23,
                                                            'y' => 2.23,
                                                        ),
                                                    'transform' =>
                                                        array(
                                                            'x' => 0.0,
                                                            'y' => -1.0522388059701493,
                                                        ),
                                                ),
                                            'common_keyframes' =>
                                                array(
                                                    0 =>
                                                        array(
                                                            'id' => 'EE9D1AF3-832D-4672-B98A-5B3630655E0A',
                                                            'keyframe_list' =>
                                                                array(
                                                                    0 =>
                                                                        array(
                                                                            'curveType' => 'Line',
                                                                            'graphID' => '',
                                                                            'id' => '760E3D6F-0A99-4273-8935-147B65C7E709',
                                                                            'left_control' =>
                                                                                array(
                                                                                    'x' => 0.0,
                                                                                    'y' => 0.0,
                                                                                ),
                                                                            'right_control' =>
                                                                                array(
                                                                                    'x' => 0.0,
                                                                                    'y' => 0.0,
                                                                                ),
                                                                            'time_offset' => 433333,
                                                                            'values' =>
                                                                                array(
                                                                                    0 => 1.0,
                                                                                    1 => 1.0,
                                                                                ),
                                                                        ),
                                                                    1 =>
                                                                        array(
                                                                            'curveType' => 'Line',
                                                                            'graphID' => '',
                                                                            'id' => '2DB2D518-8EFD-489b-8B65-EEFC702D2F57',
                                                                            'left_control' =>
                                                                                array(
                                                                                    'x' => 0.0,
                                                                                    'y' => 0.0,
                                                                                ),
                                                                            'right_control' =>
                                                                                array(
                                                                                    'x' => 0.0,
                                                                                    'y' => 0.0,
                                                                                ),
                                                                            'time_offset' => 2433333,
                                                                            'values' =>
                                                                                array(
                                                                                    0 => 2.23,
                                                                                    1 => 2.23,
                                                                                ),
                                                                        ),
                                                                ),
                                                            'property_type' => 'KFTypeScale',
                                                        ),
                                                ),
                                            'enable_adjust' => true,
                                            'enable_color_curves' => true,
                                            'enable_color_wheels' => true,
                                            'enable_lut' => true,
                                            'enable_smart_color_adjust' => false,
                                            'extra_material_refs' =>
                                                array(
                                                    0 => '86B51E0F-E278-4807-9AD1-54EB80D94DDC',
                                                    1 => '7F7F2680-A137-41ea-AF11-C7D9DC5A0472',
                                                    2 => '15B7DF55-BC5A-43a7-8654-0A49FB3761A3',
                                                    3 => '9F712746-198B-44e0-A0D5-1A3E6AF6B537',
                                                ),
                                            'group_id' => '',
                                            'hdr_settings' =>
                                                array(
                                                    'intensity' => 1.0,
                                                    'mode' => 1,
                                                    'nits' => 1000,
                                                ),
                                            'id' => 'FC26DA86-C9E1-4720-8436-F3688AAE15B9',
                                            'intensifies_audio' => false,
                                            'is_placeholder' => false,
                                            'is_tone_modify' => false,
                                            'keyframe_refs' =>
                                                array(),
                                            'last_nonzero_volume' => 1.0,
                                            'material_id' => '3963376E-4C2A-4fde-8A0C-94FC7F72398F',
                                            'render_index' => 2,
                                            'reverse' => false,
                                            'source_timerange' =>
                                                array(
                                                    'duration' => 3599999,
                                                    'start' => 0,
                                                ),
                                            'speed' => 1.0,
                                            'target_timerange' =>
                                                array(
                                                    'duration' => 3600000,
                                                    'start' => 5000000,
                                                ),
                                            'template_id' => '',
                                            'template_scene' => 'default',
                                            'track_attribute' => 0,
                                            'track_render_index' => 0,
                                            'visible' => true,
                                            'volume' => 1.0,
                                        ),
                                    2 =>
                                        array(
                                            'cartoon' => false,
                                            'clip' =>
                                                array(
                                                    'alpha' => 1.0,
                                                    'flip' =>
                                                        array(
                                                            'horizontal' => false,
                                                            'vertical' => false,
                                                        ),
                                                    'rotation' => 0.0,
                                                    'scale' =>
                                                        array(
                                                            'x' => 1.0,
                                                            'y' => 1.0,
                                                        ),
                                                    'transform' =>
                                                        array(
                                                            'x' => 0.0,
                                                            'y' => 0.0,
                                                        ),
                                                ),
                                            'common_keyframes' =>
                                                array(),
                                            'enable_adjust' => true,
                                            'enable_color_curves' => true,
                                            'enable_color_wheels' => true,
                                            'enable_lut' => true,
                                            'enable_smart_color_adjust' => false,
                                            'extra_material_refs' =>
                                                array(
                                                    0 => '6639EE1F-167E-4c7c-B23E-CBB64E4F7FAC',
                                                    1 => 'E9238034-1F7B-4f67-AB37-7FAD6E62C288',
                                                    2 => '8289ED99-BB20-49d7-B509-BA8A34A809D2',
                                                    3 => 'B2DD17F8-3C4D-4f1c-8917-FFAE705834B8',
                                                    4 => '08012241-F550-46fd-B60E-2E2A893B32CA',
                                                ),
                                            'group_id' => '',
                                            'hdr_settings' =>
                                                array(
                                                    'intensity' => 1.0,
                                                    'mode' => 1,
                                                    'nits' => 1000,
                                                ),
                                            'id' => '39A6836D-251C-43b0-ADED-B2B7CC9A7F8B',
                                            'intensifies_audio' => false,
                                            'is_placeholder' => false,
                                            'is_tone_modify' => false,
                                            'keyframe_refs' =>
                                                array(),
                                            'last_nonzero_volume' => 1.0,
                                            'material_id' => 'C61173C8-7F8E-4a5b-B4FA-23B2C4DD9A0B',
                                            'render_index' => 1,
                                            'reverse' => false,
                                            'source_timerange' =>
                                                array(
                                                    'duration' => 6400000,
                                                    'start' => 0,
                                                ),
                                            'speed' => 1.0,
                                            'target_timerange' =>
                                                array(
                                                    'duration' => 6400000,
                                                    'start' => 8600000,
                                                ),
                                            'template_id' => '',
                                            'template_scene' => 'default',
                                            'track_attribute' => 0,
                                            'track_render_index' => 0,
                                            'visible' => true,
                                            'volume' => 1.0,
                                        ),
                                ),
                            'type' => 'video',
                        ),
                    2 =>
                        array(
                            'attribute' => 0,
                            'flag' => 0,
                            'id' => '6A9D9941-5524-4f08-86C7-4847A7C24A34',
                            'segments' =>
                                array(
                                    0 =>
                                        array(
                                            'cartoon' => false,
                                            'clip' =>
                                                array(
                                                    'alpha' => 1.0,
                                                    'flip' =>
                                                        array(
                                                            'horizontal' => false,
                                                            'vertical' => false,
                                                        ),
                                                    'rotation' => 0.0,
                                                    'scale' =>
                                                        array(
                                                            'x' => 1.0,
                                                            'y' => 1.0,
                                                        ),
                                                    'transform' =>
                                                        array(
                                                            'x' => 0.0,
                                                            'y' => 0.0,
                                                        ),
                                                ),
                                            'common_keyframes' =>
                                                array(),
                                            'enable_adjust' => false,
                                            'enable_color_curves' => true,
                                            'enable_color_wheels' => true,
                                            'enable_lut' => false,
                                            'enable_smart_color_adjust' => false,
                                            'extra_material_refs' =>
                                                array(
                                                    0 => '2AC00497-20F1-4301-957E-713C4C22B19F',
                                                ),
                                            'group_id' => '',
                                            'hdr_settings' => NULL,
                                            'id' => '0065623A-503C-4291-B93F-9D24C4AD831B',
                                            'intensifies_audio' => false,
                                            'is_placeholder' => false,
                                            'is_tone_modify' => false,
                                            'keyframe_refs' =>
                                                array(),
                                            'last_nonzero_volume' => 1.0,
                                            'material_id' => 'CE9C004D-93DF-4445-8320-BF4BEA9556B5',
                                            'render_index' => 14006,
                                            'reverse' => false,
                                            'source_timerange' => NULL,
                                            'speed' => 1.0,
                                            'target_timerange' =>
                                                array(
                                                    'duration' => 6766666,
                                                    'start' => 0,
                                                ),
                                            'template_id' => '',
                                            'template_scene' => 'default',
                                            'track_attribute' => 0,
                                            'track_render_index' => 0,
                                            'visible' => true,
                                            'volume' => 1.0,
                                        ),
                                    1 =>
                                        array(
                                            'cartoon' => false,
                                            'clip' =>
                                                array(
                                                    'alpha' => 1.0,
                                                    'flip' =>
                                                        array(
                                                            'horizontal' => false,
                                                            'vertical' => false,
                                                        ),
                                                    'rotation' => 0.0,
                                                    'scale' =>
                                                        array(
                                                            'x' => 1.0,
                                                            'y' => 1.0,
                                                        ),
                                                    'transform' =>
                                                        array(
                                                            'x' => 0.0,
                                                            'y' => 0.0,
                                                        ),
                                                ),
                                            'common_keyframes' =>
                                                array(),
                                            'enable_adjust' => false,
                                            'enable_color_curves' => true,
                                            'enable_color_wheels' => true,
                                            'enable_lut' => false,
                                            'enable_smart_color_adjust' => false,
                                            'extra_material_refs' =>
                                                array(
                                                    0 => 'C4B57D9B-9649-44b9-867F-443861FD4C78',
                                                ),
                                            'group_id' => '',
                                            'hdr_settings' => NULL,
                                            'id' => '3D9E03CD-BDEC-4416-A11C-7FC808D94D02',
                                            'intensifies_audio' => false,
                                            'is_placeholder' => false,
                                            'is_tone_modify' => false,
                                            'keyframe_refs' =>
                                                array(),
                                            'last_nonzero_volume' => 1.0,
                                            'material_id' => '120E49B1-A52D-4dbd-95BA-E59E7222EFAC',
                                            'render_index' => 14005,
                                            'reverse' => false,
                                            'source_timerange' => NULL,
                                            'speed' => 1.0,
                                            'target_timerange' =>
                                                array(
                                                    'duration' => 8233333,
                                                    'start' => 6766666,
                                                ),
                                            'template_id' => '',
                                            'template_scene' => 'default',
                                            'track_attribute' => 0,
                                            'track_render_index' => 0,
                                            'visible' => true,
                                            'volume' => 1.0,
                                        ),
                                ),
                            'type' => 'text',
                        ),
                ),
            'update_time' => 0,
            'version' => 360000,
        );
        $imgs = [];
        $k = 0;
        //素材库
        for ($i = 1; $i < 10; $i++) {
            $imgs[$k]["path"] = "C:/Users/shiyi/Desktop/ceshi/" . $i . ".jpg";
            $imgs[$k]["name"] = "C:/Users/shiyi/Desktop/ceshi/" . $i . ".jpg";
            $imgs[$k]["md5"] = $this->dmd5($imgs[$k]["path"]);
            $k++;
        }
        $imgmuban = array(
            'audio_fade' => NULL,
            'cartoon_path' => '',
            'category_id' => '',
            'category_name' => '',
            'check_flag' => 63487,
            'crop' =>
                array(
                    'lower_left_x' => 0.0,
                    'lower_left_y' => 1.0,
                    'lower_right_x' => 1.0,
                    'lower_right_y' => 1.0,
                    'upper_left_x' => 0.0,
                    'upper_left_y' => 0.0,
                    'upper_right_x' => 1.0,
                    'upper_right_y' => 0.0,
                ),
            'crop_ratio' => 'free',
            'crop_scale' => 1.0,
            'duration' => 10800000000,
            'extra_type_option' => 0,
            'formula_id' => '',
            'freeze' => NULL,
            'gameplay' => NULL,
            'has_audio' => false,
            'height' => 998,
            'id' => '6481C2B4-DC0C-41b9-9778-D7DB7390F181',
            'intensifies_audio_path' => '',
            'intensifies_path' => '',
            'is_unified_beauty_mode' => false,
            'local_id' => '',
            'local_material_id' => '',
            'material_id' => '',
            'material_name' => '1.jpg',
            'material_url' => '',
            'matting' =>
                array(
                    'flag' => 0,
                    'has_use_quick_brush' => false,
                    'has_use_quick_eraser' => false,
                    'interactiveTime' =>
                        array(),
                    'path' => '',
                    'strokes' =>
                        array(),
                ),
            'media_path' => '',
            'object_locked' => NULL,
            'path' => 'C:/Users/shiyi/Desktop/ceshi/1.jpg',
            'picture_from' => 'none',
            'picture_set_category_id' => '',
            'picture_set_category_name' => '',
            'request_id' => '',
            'reverse_intensifies_path' => '',
            'reverse_path' => '',
            'source_platform' => 0,
            'stable' => NULL,
            'team_id' => '',
            'type' => 'photo',
            'video_algorithm' =>
                array(
                    'algorithms' =>
                        array(),
                    'deflicker' => NULL,
                    'motion_blur_config' => NULL,
                    'noise_reduction' => NULL,
                    'path' => '',
                    'time_range' => NULL,
                ),
            'width' => 448,
        );
        //生成100个剪映素材库
        // //https://api.hxfox.com/twtool/Cut/index
        $imggd = array(  //轨道上 图片模板
            'cartoon' => false,
            'clip' =>
                array(
                    'alpha' => 1.0,
                    'flip' =>
                        array(
                            'horizontal' => false,
                            'vertical' => false,
                        ),
                    'rotation' => 0.0,
                    'scale' =>
                        array(
                            'x' => 1.0,
                            'y' => 1.0,
                        ),
                    'transform' =>
                        array(
                            'x' => 0.0,
                            'y' => 0,
                        ),
                ),
            'common_keyframes' =>
                array(
                    0 =>
                        array(
                            'id' => '62FC7F4F-FC5F-4c8a-AAF1-089DC71EA765',
                            'keyframe_list' =>
                                array(
                                    0 =>
                                        array(
                                            'curveType' => 'Line',
                                            'graphID' => '',
                                            'id' => '9523BC76-97E6-4ddf-A0A7-4C92B9325597',
                                            'left_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'right_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'time_offset' => 0,
                                            'values' =>
                                                array(
                                                    0 => 0,
                                                ),
                                        ),
                                    1 =>
                                        array(
                                            'curveType' => 'Line',
                                            'graphID' => '',
                                            'id' => '74BE9F7B-8ACC-4789-B39E-C4B48E66C9FD',
                                            'left_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'right_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'time_offset' => 5000000,
                                            'values' =>
                                                array(
                                                    0 => 0.1, //X轴  向右运动
                                                ),
                                        ),
                                ),
                            'property_type' => 'KFTypePositionX',
                        ),
                    1 =>
                        array(
                            'id' => 'F02BEB57-CA34-404e-8DA1-EDD10EA4FC4F',
                            'keyframe_list' =>
                                array(
                                    0 =>
                                        array(
                                            'curveType' => 'Line',
                                            'graphID' => '',
                                            'id' => '10CD7F44-A624-4177-8CFB-BCC8D7A9E325',
                                            'left_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'right_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'time_offset' => 18666666,
                                            'values' =>
                                                array(
                                                    0 => 0,
                                                ),
                                        ),
                                    1 =>
                                        array(
                                            'curveType' => 'Line',
                                            'graphID' => '',
                                            'id' => '543636F5-A5F1-4b1e-B1AA-618556FABEDA',
                                            'left_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'right_control' =>
                                                array(
                                                    'x' => 0.0,
                                                    'y' => 0.0,
                                                ),
                                            'time_offset' => 18666666,
                                            'values' =>
                                                array(
                                                    0 => 4,  //Y轴  向上运动
                                                ),
                                        ),
                                ),
                            'property_type' => 'KFTypePositionY',
                        ),
                ),
            'enable_adjust' => true,
            'enable_color_curves' => true,
            'enable_color_wheels' => true,
            'enable_lut' => true,
            'enable_smart_color_adjust' => false,
            'extra_material_refs' =>
                array(
                    0 => 'AF11AEFB-C5ED-4917-92C5-437E8B049552',
                    1 => '45BAA080-56D0-48f7-A475-C2FF291FE106',
                    2 => '10443C28-7A4E-40df-8712-E8269E517974',
                    3 => 'D33F5375-F800-4636-9EE1-71364C19D248',
                ),
            'group_id' => '',
            'hdr_settings' =>
                array(
                    'intensity' => 1.0,
                    'mode' => 1,
                    'nits' => 1000,
                ),
            'id' => 'BBCF1C2D-DE3A-41b3-A83B-A045BB9A5290',
            'intensifies_audio' => false,
            'is_placeholder' => false,
            'is_tone_modify' => false,
            'keyframe_refs' =>
                array(),
            'last_nonzero_volume' => 1.0,
            'material_id' => '6481C2B4-DC0C-41b9-9778-D7DB7390F181',  //1.jpg
            'render_index' => 1,
            'reverse' => false,
            'source_timerange' =>
                array(
                    'duration' => 5000000,  //5秒
                    'start' => 0,
                ),
            'speed' => 1.0,
            'target_timerange' =>
                array(
                    'duration' => 5000000,
                    'start' => 0,
                ),
            'template_id' => '',
            'template_scene' => 'default',
            'track_attribute' => 0,
            'track_render_index' => 0,
            'visible' => true,
            'volume' => 1.0,
        );

        $text = array(
            'add_type' => 0,
            'alignment' => 1,
            'background_alpha' => 1.0,
            'background_color' => '',
            'background_height' => 1.0,
            'background_horizontal_offset' => 0.0,
            'background_round_radius' => 0.0,
            'background_style' => 0,
            'background_vertical_offset' => 0.0,
            'background_width' => 1.0,
            'bold_width' => 0.0,
            'border_color' => '#000000',
            'border_width' => 0.08,
            'check_flag' => 15,
            'content' => '<outline color=(0,0,0,1) width=0.08><size=15><color=(1,0.87059,0,1)><font id="6740435892441190919" path="C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/新青年体.ttf">[我是第一个文本]</font></color></size></outline>',
            'font_category_id' => '',
            'font_category_name' => '',
            'font_id' => '',
            'font_name' => '',
            'font_path' => 'C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/新青年体.ttf',
            'font_resource_id' => '6740435892441190919',
            'font_size' => 15.0,
            'font_source_platform' => 0,
            'font_team_id' => '',
            'font_title' => 'none',
            'font_url' => '',
            'fonts' =>
                array(
                    0 =>
                        array(
                            'category_id' => '',
                            'category_name' => '',
                            'effect_id' => '6740435892441190919',
                            'id' => 'A7D697F3-DD61-44bd-B2E0-38EEAE6C6227',
                            'path' => 'C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/新青年体.ttf',
                            'resource_id' => '6740435892441190919',
                            'source_platform' => 0,
                            'team_id' => '',
                            'title' => '新青年体',
                        ),
                ),
            'force_apply_line_max_width' => false,
            'global_alpha' => 1.0,
            'group_id' => '',
            'has_shadow' => false,
            'id' => 'CE9C004D-93DF-4445-8320-BF4BEA9556B5',
            'initial_scale' => 1.0,
            'is_rich_text' => false,
            'italic_degree' => 0,
            'ktv_color' => '',
            'language' => '',
            'layer_weight' => 1,
            'letter_spacing' => 0.0,
            'line_spacing' => 0.02,
            'name' => '',
            'preset_category' => '',
            'preset_category_id' => '',
            'preset_has_set_alignment' => false,
            'preset_id' => '',
            'preset_index' => 0,
            'preset_name' => '',
            'recognize_type' => 0,
            'relevance_segment' =>
                array(),
            'shadow_alpha' => 0.0,
            'shadow_angle' => -45.0,
            'shadow_color' => '#000000',
            'shadow_distance' => 8.0,
            'shadow_point' =>
                array(
                    'x' => 1.0182337649086284,
                    'y' => -1.0182337649086284,
                ),
            'shadow_smoothing' => 0.99,
            'shape_clip_x' => false,
            'shape_clip_y' => false,
            'style_name' => '黄字黑边',
            'sub_type' => 0,
            'text_alpha' => 1.0,
            'text_color' => '#ffde00',
            'text_preset_resource_id' => '',
            'text_size' => 15,
            'text_to_audio_ids' =>
                array(),
            'tts_auto_update' => false,
            'type' => 'text',
            'typesetting' => 0,
            'underline' => false,
            'underline_offset' => 0.22,
            'underline_width' => 0.05,
            'use_effect_default_color' => false,
            'words' =>
                array(),
        );
        $textgd = array(
            'cartoon' => false,
            'clip' =>
                array(
                    'alpha' => 1.0,
                    'flip' =>
                        array(
                            'horizontal' => false,
                            'vertical' => false,
                        ),
                    'rotation' => 0.0,
                    'scale' =>
                        array(
                            'x' => 1.0,
                            'y' => 1.0,
                        ),
                    'transform' =>
                        array(
                            'x' => 0.0,
                            'y' => $this->weizhi, //文字靠下
                        ),
                ),
            'common_keyframes' =>
                array(),
            'enable_adjust' => false,
            'enable_color_curves' => true,
            'enable_color_wheels' => true,
            'enable_lut' => false,
            'enable_smart_color_adjust' => false,
            'extra_material_refs' =>
                array(
                    0 => '2AC00497-20F1-4301-957E-713C4C22B19F',
                ),
            'group_id' => '',
            'hdr_settings' => NULL,
            'id' => '0065623A-503C-4291-B93F-9D24C4AD831B',
            'intensifies_audio' => false,
            'is_placeholder' => false,
            'is_tone_modify' => false,
            'keyframe_refs' =>
                array(),
            'last_nonzero_volume' => 1.0,
            'material_id' => 'CE9C004D-93DF-4445-8320-BF4BEA9556B5',
            'render_index' => 14006,
            'reverse' => false,
            'source_timerange' => NULL,
            'speed' => 1.0,
            'target_timerange' =>
                array(
                    'duration' => 6766666,
                    'start' => 0,
                ),
            'template_id' => '',
            'template_scene' => 'default',
            'track_attribute' => 0,
            'track_render_index' => 0,
            'visible' => true,
            'volume' => 1.0,
        );
        $audio = array(
            'app_id' => 0,
            'category_id' => '',
            'category_name' => '',
            'check_flag' => 1,
            'duration' => 184466666,
            'effect_id' => '',
            'formula_id' => '',
            'id' => 'EBE8D9C0-7FF5-45da-AAA0-F72535E0EC12',
            'intensifies_path' => '',
            'local_material_id' => '',
            'music_id' => '',
            'name' => '1.mp3',
            'path' => 'C:/Users/shiyi/Desktop/ceshi/1.mp3',
            'request_id' => '',
            'resource_id' => '',
            'source_platform' => 0,
            'team_id' => '',
            'text_id' => '',
            'tone_category_id' => '',
            'tone_category_name' => '',
            'tone_effect_id' => '',
            'tone_effect_name' => '',
            'tone_speaker' => '',
            'tone_type' => '',
            'type' => 'extract_music',
            'video_id' => '',
            'wave_points' =>
                array(),
        );
        $audiogd = array(
            'cartoon' => false,
            'clip' => NULL,
            'common_keyframes' =>
                array(),
            'enable_adjust' => false,
            'enable_color_curves' => true,
            'enable_color_wheels' => true,
            'enable_lut' => false,
            'enable_smart_color_adjust' => false,
            'extra_material_refs' =>
                array(
                    0 => '11276E8E-99AB-46a4-A540-A0CED299B9CC',
                    1 => '153364D7-E047-4f64-BD88-FD762C30AE8B',
                    2 => '9F4B60B2-CC76-4e29-B498-09E560E8E199',
                ),
            'group_id' => '',
            'hdr_settings' => NULL,
            'id' => 'C3399AB7-89E0-4929-BBFE-19493E7A9598',
            'intensifies_audio' => false,
            'is_placeholder' => false,
            'is_tone_modify' => false,
            'keyframe_refs' =>
                array(),
            'last_nonzero_volume' => 1.0,
            'material_id' => 'EBE8D9C0-7FF5-45da-AAA0-F72535E0EC12',
            'render_index' => 0,
            'reverse' => false,
            'source_timerange' =>
                array(
                    'duration' => 184466666,
                    'start' => 0,
                ),
            'speed' => 1.0,
            'target_timerange' =>
                array(
                    'duration' => 184466666,
                    'start' => 0,
                ),
            'template_id' => '',
            'template_scene' => 'default',
            'track_attribute' => 0,
            'track_render_index' => 0,
            'visible' => true,
            'volume' => 1.0,
        );

        $videos = []; //素材库
        $segments = [];  //时间轨道
        $stime = 0;
        //图片组
        foreach ($arr["imgs"] as $k => $v) {

            $videos[$k] = $imgmuban;
            $videos[$k]['path'] = $v["path"];
            $videos[$k]['width'] = (int)$v["width"];
            $videos[$k]['height'] = (int)$v["height"];
            $videos[$k]['material_name'] = $v["name"];
            $videos[$k]['id'] = $this->dmd5($v["name"] . $k);
            //多 放大比例
            $bili = 1 + $this->ov;
            //计算放大 多少
            //计算宽还是高 铺满
            if ($arr["config"]["width"] / $arr["config"]["height"] > $v["width"] / $v["height"]) { //高铺满
                $s = $arr["config"]["width"] * $bili / ($v["width"] / ($v["height"] / $arr["config"]["height"]));
            } else {
                $s = $arr["config"]["height"] * $bili / ($v["height"] / ($v["width"] / $arr["config"]["width"]));
            }

            $segments[$k] = $imggd;
            $segments[$k]['id'] = $this->dmd5("segments_imgs" . $k);
            $segments[$k]['material_id'] = $videos[$k]['id'];

            if ($this->istx) { //是否开启随机特效
                $rand = rand(1, 2);//随机特效
                if ($rand == 1) {  //随机特效
                    $tx = "07C7682E-DBA7-4719-87C2-F1A1BCE304A8";  //对应的特效ID
                } else {
                    $tx = "1903EF86-ECBA-4f52-99C3-AED44525A142";
                }

                $segments[$k]['extra_material_refs'][2] = $imggd['extra_material_refs'][1];
                $segments[$k]['extra_material_refs'][3] = $imggd['extra_material_refs'][2];
                $segments[$k]['extra_material_refs'][4] = $imggd['extra_material_refs'][3];
                $segments[$k]['extra_material_refs'][1] = $tx; //随机特效
            }


            $segments[$k]['source_timerange']["duration"] = (int)$v["duration"];
            $segments[$k]['target_timerange']["duration"] = (int)$v["duration"];
            $segments[$k]['target_timerange']["start"] = (int)$stime;

            $segments[$k]['clip']["scale"]["x"] = (int)$s;
            $segments[$k]['clip']["scale"]["y"] = (int)$s;
            $segments[$k]['common_keyframes'] = $this->keyf($v["key"], (float)$v["speed"], (int)$v["duration"], $s);
            $stime += (int)$v["duration"];
        }
        //字幕组
        $texts = [];
        $segments2 = [];
        // $stime = 0;
        // $stime = 0;
        foreach ($arr["texts"] as $k => $v) {
            //文字大小

            $texts[$k] = $text;

            $texts[$k]['content'] = '<outline color=(0,0,0,1) width=0.08><size=' . $this->size . '><color=(1,0.87059,0,1)><font id="6740435892441190919" path="C:/Users/shiyi/AppData/Local/JianyingPro/Apps/4.2.0.10100/Resources/Font/新青年体.ttf">[' . $v["text"] . ']</font></color></size></outline>';

            $texts[$k]['id'] = $this->dmd5("text" . $k);

            $segments2[$k] = $textgd;
            $segments2[$k]['id'] = $this->dmd5("segments_texts" . $k);
            $segments2[$k]['material_id'] = $texts[$k]['id'];
            $segments2[$k]['target_timerange']["duration"] = (int)$v["duration"];
            $segments2[$k]['target_timerange']["start"] = (int)$v["start"];

        }

        $audios = [];
        $segments3 = [];
        $stime = 0;
        foreach ($arr["audios"] as $k => $v) {

            $audios[$k] = $audio;

            $audios[$k]['path'] = $v['path'];
            $audios[$k]['name'] = $v['name'];
            $audios[$k]['duration'] = (int)$v['duration'];

            $audios[$k]['id'] = $this->dmd5("audio" . $k);

            $segments3[$k] = $audiogd;
            $segments3[$k]['id'] = $this->dmd5("segments_audios" . $k);
            $segments3[$k]['material_id'] = $audios[$k]['id'];
            $segments3[$k]['source_timerange']["duration"] = (int)$v["cduration"];
            $segments3[$k]['target_timerange']["duration"] = (int)$v["cduration"];
            $segments3[$k]['target_timerange']["start"] = (int)$v["start"];
            $stime += (int)$v["duration"];
        }

        $gdmb = array(    //轨道  模板
            'attribute' => 0,
            'flag' => 0,
            'id' => '8C6526C3-AC14-4cc7-8DFE-924BD6173AFD',
            'segments' => [],
            'type' => 'video',
        );
        $gdmb2 = array(    //轨道  模板
            'attribute' => 0,
            'flag' => 0,
            'id' => '8C6526C3-AC14-4cc7-8DFE-924BD6173ADD',
            'segments' => [],
            'type' => 'text',
        );
        $gdmb3 = array(    //轨道  模板
            'attribute' => 0,
            'flag' => 0,
            'id' => '8C6526C3-AC14-4cc7-8DFE-924BD6173ADR',
            'segments' => [],
            'type' => 'audio',
        );
        $array["duration"] = (int)$arr["config"]["duration"];
        $array["materials"]['videos'] = $videos;
        $array["materials"]['texts'] = $texts;
        $array["materials"]['audios'] = $audios;
        $gdmb["segments"] = $segments;
        $gdmb2["segments"] = $segments2;
        $gdmb3["segments"] = $segments3;
        $array['tracks'] = [

            $gdmb, $gdmb2, //$gdmb3
        ];

        $wjj = date('YmdHis',time());


        mkdir($uri . '/' . $wjj . '/', 0777, $uri);
        $filename = $uri . '/' . $wjj . '/';
        $c_result = json_encode($array);
        $c_filename = $filename . "draft_content.json";
        $c_myfile = fopen($c_filename, "w") or die("Unable to open file!");
        fwrite($c_myfile, $c_result);
        fclose($c_myfile);
        $left_jianyin = $this->m($arr['config'],$jianyin_prams_img);
        $m_result = json_encode($left_jianyin);
        $m_filename = $filename . "draft_meta_info.json";
        $m_myfile = fopen($m_filename, "w") or die("Unable to open file!");
        fwrite($m_myfile, $m_result);
        fclose($m_myfile);

        $res['code'] = 1;
        $res['show'] = 1;
        $res['msg'] = '处理成功';
        $res['data'] = [];
        return json($res);
        return $this->data(["c" => $array, "m" => $this->m($arr["config"])]);
    }

    function m($config,$jianyin_prams_img)
    {
        if ($jianyin_prams_img) {
            foreach ($jianyin_prams_img as $key => &$value) {
                $value['create_time'] = time();
                $value['duration'] = $value['duration'];
                $value['extra_info'] = $value['name'];
                $value['file_Path'] = $value['path'];
                $value['height'] = $value['height'];
                $value['id'] = $this->dmd5("segments5" . $key);
                $value['import_time'] = time();
                $value['import_time_ms'] = time() * 1000;
                $value['md5'] = '';
                $value['metetype'] = 'photo';
                $value['roughcut_time_range'] = array(
                    "duration" => -1,
                    "start" => -1
                );
                $value['sub_time_range'] = array(
                    "duration" => -1,
                    "start" => -1
                );
                $value['type'] = 0;
                $value['width'] = $value['width'];
                unset($value['key']);
                unset($value['speed']);

            }
        }

        $arr =
            array(
                'draft_cloud_last_action_download' => false,
                'draft_cloud_purchase_info' => '',
                'draft_cloud_template_id' => '',
                'draft_cloud_tutorial_info' => '',
                'draft_cloud_videocut_purchase_info' => '',
                'draft_cover' => 'draft_cover.jpg',
                'draft_deeplink_url' => '',
                'draft_enterprise_info' =>
                    array(
                        'draft_enterprise_extra' => '',
                        'draft_enterprise_id' => '',
                        'draft_enterprise_name' => '',
                    ),
                'draft_fold_path' => $config["draft_root_path"] . "/" . $config["draft_name"],
                'draft_id' => '3D58FA5D-8062-4ab3-9EB6-2D85F7C53778',
                'draft_is_article_video_draft' => false,
                'draft_is_from_deeplink' => 'false',
                'draft_materials' =>
                    array(
                        0 =>
                            array(
                                'type' => 0,
                                'value' =>
                                    $jianyin_prams_img
                            ),
                        1 =>
                            array(
                                'type' => 1,
                                'value' =>
                                    array(),
                            ),
                        2 =>
                            array(
                                'type' => 2,
                                'value' =>
                                    array(),
                            ),
                        3 =>
                            array(
                                'type' => 3,
                                'value' =>
                                    array(),
                            ),
                        4 =>
                            array(
                                'type' => 6,
                                'value' =>
                                    array(),
                            ),
                        5 =>
                            array(
                                'type' => 7,
                                'value' =>
                                    array(),
                            ),
                        6 =>
                            array(
                                'type' => 8,
                                'value' =>
                                    array(),
                            ),
                    ),
                'draft_materials_copied_info' =>
                    array(),
                'draft_name' => $config["draft_name"],
                'draft_removable_storage_device' => '',
                'draft_root_path' => $config["draft_root_path"],
                'draft_segment_extra_info' =>
                    array(),
                'draft_timeline_materials_size_' => 0,
                'tm_draft_cloud_completed' => '',
                'tm_draft_cloud_modified' => 0,
                'tm_draft_create' => time() * 1000000,
                'tm_draft_modified' => time() * 1000000,
                'tm_duration' => (int)$config["duration"],
            );

        return $arr;


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

    function moni()
    {


        $array = [
            "config" => [
                "width" => 2016,            //视频画布宽
                "height" => 1440,            //视频画布高
                "fps" => 30.0,                //视频帧率   每秒刷新率
                'duration' => 150000000, //总持续时间 秒*1000000
                'draft_root_path' => '', //草稿所在文件夹 完整本地路径
                'draft_name' => date('YmdHi'), //草稿名称
            ],
            "imgs" => [      //图片轨道    运动随机   特效随机
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/1.jpg', //完整本地路径
                    'name' => '1.jpg', //文件名
                    "width" => 4480, //宽
                    "height" => 6464, //高
                    'duration' => 2000000, //持续时间 秒*1000000
                    'key' => "top", //关键帧动效    top bottom left right big small
                    'speed' => 1.0, //动效速度倍率
                    // 'start' => 1000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/2.jpg', //完整本地路径
                    'name' => '2.jpg', //文件名
                    "width" => 4095, //宽
                    "height" => 5887, //高
                    'duration' => 1000000, //持续时间 秒*1000000
                    'key' => "left", //关键帧动效    top bottom left right big small
                    'speed' => 0.5, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/3.jpg', //完整本地路径
                    'name' => '3.jpg', //文件名
                    "width" => 2000, //宽
                    "height" => 2904, //高
                    'duration' => 2500000, //持续时间 秒*1000000
                    'key' => "right", //关键帧动效    top bottom left right big small
                    'speed' => 1.0, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/4.jpg', //完整本地路径
                    'name' => '4.jpg', //文件名
                    "width" => 3000, //宽
                    "height" => 4348, //高
                    'duration' => 1500000, //持续时间 秒*1000000
                    'key' => "bottom", //关键帧动效    top bottom left right big small
                    'speed' => 0.04, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/5.jpg', //完整本地路径
                    'name' => '5.jpg', //文件名
                    "width" => 4480, //宽
                    "height" => 6496, //高
                    'duration' => 4000000, //持续时间 秒*1000000
                    'key' => "left", //关键帧动效    top bottom left right big small
                    'speed' => 2.0, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/6.jpg', //完整本地路径
                    'name' => '6.jpg', //文件名
                    "width" => 4096, //宽
                    "height" => 2304, //高
                    'duration' => 2100000, //持续时间 秒*1000000
                    'key' => "bottom", //关键帧动效    top bottom left right big small
                    'speed' => 0.001, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/7.jpg', //完整本地路径
                    'name' => '7.jpg', //文件名
                    "width" => 4480, //宽
                    "height" => 6400, //高
                    'duration' => 2300000, //持续时间 秒*1000000
                    'key' => "big", //关键帧动效    top bottom left right big small
                    'speed' => 100.0, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/8.jpg', //完整本地路径
                    'name' => '8.jpg', //文件名
                    "width" => 4480, //宽
                    "height" => 6580, //高
                    'duration' => 2400000, //持续时间 秒*1000000
                    'key' => "small", //关键帧动效    top bottom left right big small
                    'speed' => 1.0, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/9.jpg', //完整本地路径
                    'name' => '9.jpg', //文件名
                    "width" => 4000, //宽
                    "height" => 6000, //高
                    'duration' => 2500000, //持续时间 秒*1000000
                    'key' => "big", //关键帧动效    top bottom left right big small
                    'speed' => 1.5, //动效速度倍率
                    // 'start' => 16000000, //开始时间 秒*1000000
                ],


            ],
            "texts" => [      //字幕轨道    样式随机
                [
                    'text' => '我是我是我是我是第一个文本',
                    'duration' => 4000000, //持续时间 秒*1000000
                    'start' => 1000000, //开始时间 秒*1000000
                ],
                [
                    'text' => '我是我是我是我是第二个文本',
                    'duration' => 5000000, //持续时间 秒*1000000
                    'start' => 17000000, //开始时间 秒*1000000
                ],
            ],
            "audios" => [  //配音
                [
                    'path' => 'C:/Users/shiyi/Desktop/ceshi/1.mp3', //完整本地路径
                    'name' => '1.mp3', //文件名
                    'duration' => 184466666, //原素材持续时间 秒*1000000
                    'cduration' => 20000000, //裁切后持续时间 秒*1000000
                    'start' => 0, //开始时间 秒*1000000
                ]


            ]
        ];

        //return $this->index(json_encode($array));
        return $array;
    }

    //随机运动
    function keyf($k, $sp, $d, $s)
    {  //$k 关键帧动效 $sp 速度倍率  $d 持续时间  $s  当前素材缩放值
        //$arr=[0,0,$this->ov];
        //shuffle($arr);
        //$rand=rand(1,2);
        //if($rand==1){
        //	$zf=1;
        //}else{
        //	$zf= -1;
        //}
        $ov = $this->ov;
        $dd = 20000000 / $sp;
        if ($d < $dd) {
            $dd = $d;
            $ov = $ov * $d / $dd;
        }
        if ($k == "top") {
            $zf = -1;
            $arr = [0, $ov, 0];
        } elseif ($k == "bottom") {
            $zf = 1;
            $arr = [0, $ov, 0];
        } elseif ($k == "left") {
            $arr = [$ov, 0, 0];
            $zf = 1;
        } elseif ($k == "right") {
            $arr = [$ov, 0, 0];
            $zf = -1;
        } elseif ($k == "big") {
            $arr = [0, 0, $ov];
            $zf = -1;
        } elseif ($k == "small") {
            $zf = 1;
            $arr = [0, 0, $ov];
        }


        if ($arr[2] > 0) {
            $arr[2] = $zf * $arr[2] + $s;
        } else {
            $arr[2] = $s;
        }

        $keyf =
            array(
                0 =>
                    array(
                        'id' => $this->dmd5("KFTypePositionX" . rand(10000000, 99999999)),
                        'keyframe_list' =>
                            array(
                                0 =>
                                    array(
                                        'curveType' => 'Line',
                                        'graphID' => '',
                                        'id' => $this->dmd5("KFTypePositionX0" . rand(10000000, 99999999)),
                                        'left_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'right_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'time_offset' => 0,
                                        'values' => array(
                                            0 => 0.0
                                        ),

                                    ),
                                1 =>
                                    array(
                                        'curveType' => 'Line',
                                        'graphID' => '',
                                        'id' => $this->dmd5("KFTypePositionX1" . rand(10000000, 99999999)),
                                        'left_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'right_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'time_offset' => $dd,
                                        'values' => array(
                                            0 => $zf * $arr[0]
                                        ),
                                    ),
                            ),
                        'property_type' => 'KFTypePositionX',
                    ),
                1 =>
                    array(
                        'id' => $this->dmd5("KFTypePositionY" . rand(10000000, 99999999)),
                        'keyframe_list' =>
                            array(
                                0 =>
                                    array(
                                        'curveType' => 'Line',
                                        'graphID' => '',
                                        'id' => $this->dmd5("KFTypePositionY0" . rand(10000000, 99999999)),
                                        'left_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'right_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'time_offset' => 0,
                                        'values' => array(
                                            0 => 0.0
                                        ),
                                    ),
                                1 =>
                                    array(
                                        'curveType' => 'Line',
                                        'graphID' => '',
                                        'id' => $this->dmd5("KFTypePositionY1" . rand(10000000, 99999999)),
                                        'left_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'right_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'time_offset' => $dd,
                                        'values' => array(
                                            0 => $zf * $arr[1]
                                        ),
                                    ),
                            ),
                        'property_type' => 'KFTypePositionY',
                    ),
                2 =>
                    array(
                        'id' => $this->dmd5("KFTypeScale" . rand(10000000, 99999999)),
                        'keyframe_list' =>
                            array(
                                0 =>
                                    array(
                                        'curveType' => 'Line',
                                        'graphID' => '',
                                        'id' => $this->dmd5("KFTypeScale0" . rand(10000000, 99999999)),
                                        'left_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'right_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'time_offset' => 0,
                                        'values' =>
                                            array(
                                                0 => $s,
                                                1 => $s,
                                            ),
                                    ),
                                1 =>
                                    array(
                                        'curveType' => 'Line',
                                        'graphID' => '',
                                        'id' => $this->dmd5("KFTypeScale1" . rand(10000000, 99999999)),
                                        'left_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'right_control' =>
                                            array(
                                                'x' => 0.0,
                                                'y' => 0.0,
                                            ),
                                        'time_offset' => $dd,
                                        'values' =>
                                            array(
                                                0 => $arr[2],
                                                1 => $arr[2],
                                            ),
                                    ),
                            ),
                        'property_type' => 'KFTypeScale',
                    ),
            );

        return $keyf;
    }

    //再用 根据关键帧获取
    public function getOperation($text)
    {
        $is_chinese = is_chinese($text);
        if ($is_chinese) {
            switch ($text) {
                case '从左到右':
                    $title = 'left';
                    break;
                case '从右到左':
                    $title = 'right';
                    break;
                case '从上到下':
                    $title = 'top';
                    break;
                case '从下到上':
                    $title = 'bottom';
                    break;
                case '从小到大':
                    $title = 'small';
                    break;
                case '从大到小':
                    $title = 'big';
                    break;
            }
        }else{
            $title = $text;
        }



        return $title;
    }

    //https://api.hxfox.com/twtool/Cut/cc
    function cc()
    {
        $json = '{"draft_cloud_last_action_download":false,"draft_cloud_purchase_info":"","draft_cloud_template_id":"","draft_cloud_tutorial_info":"","draft_cloud_videocut_purchase_info":"","draft_cover":"draft_cover.jpg","draft_deeplink_url":"","draft_enterprise_info":{"draft_enterprise_extra":"","draft_enterprise_id":"","draft_enterprise_name":""},"draft_fold_path":"C:/Users/shiyi/AppData/Local/JianyingPro/User Data/Projects/com.lveditor.draft/白大校花","draft_id":"3D58FA5D-8062-4ab3-9EB6-2D85F7C53712","draft_is_article_video_draft":false,"draft_is_from_deeplink":"false","draft_materials":[{"type":0,"value":[{"create_time":1684287607,"duration":1240766000,"extra_info":"白大校花.mp4","file_Path":"C:/Users/shiyi/Desktop/抖音/番茄畅听/白大校花/原素材/白大校花.mp4","height":720,"id":"77ab5429-a703-48de-a8ec-963f2b7848a9","import_time":1684288025,"import_time_ms":1684288025033829,"md5":"","metetype":"video","roughcut_time_range":{"duration":1240766000,"start":0},"sub_time_range":{"duration":-1,"start":-1},"type":0,"width":1280},{"create_time":1684290102,"duration":5000000,"extra_info":"a7f62c416debbbaa72ad099d4695c7c.jpg","file_Path":"C:/Users/shiyi/Desktop/a7f62c416debbbaa72ad099d4695c7c.jpg","height":1176,"id":"cf14d37c-ee7b-4945-82a0-f4cc137bfe1c","import_time":1684290106,"import_time_ms":1684290106218830,"md5":"","metetype":"photo","roughcut_time_range":{"duration":-1,"start":-1},"sub_time_range":{"duration":-1,"start":-1},"type":0,"width":1176},{"create_time":1683816936,"duration":530483000,"extra_info":"56239.mp4","file_Path":"C:/Users/shiyi/Desktop/做饭全/56239.mp4","height":1080,"id":"50c307c4-4979-4c0a-8132-3cf426ff6250","import_time":1684293216,"import_time_ms":1684293216735370,"md5":"","metetype":"video","roughcut_time_range":{"duration":530483000,"start":0},"sub_time_range":{"duration":-1,"start":-1},"type":0,"width":1920},{"create_time":1683817014,"duration":481000000,"extra_info":"48136.mp4","file_Path":"C:/Users/shiyi/Desktop/做饭全/48136.mp4","height":1080,"id":"954a3a91-ef74-4ca4-b162-2b1b3cba5159","import_time":1684293216,"import_time_ms":1684293216735407,"md5":"","metetype":"video","roughcut_time_range":{"duration":481000000,"start":0},"sub_time_range":{"duration":-1,"start":-1},"type":0,"width":1920},{"create_time":1683816901,"duration":468483000,"extra_info":"54917.mp4","file_Path":"C:/Users/shiyi/Desktop/做饭全/54917.mp4","height":1080,"id":"7b2bb5dc-3de0-4572-b350-db0e59749f8e","import_time":1684293216,"import_time_ms":1684293216735312,"md5":"","metetype":"video","roughcut_time_range":{"duration":468483000,"start":0},"sub_time_range":{"duration":-1,"start":-1},"type":0,"width":1920}]},{"type":1,"value":[]},{"type":2,"value":[]},{"type":3,"value":[]},{"type":6,"value":[]},{"type":7,"value":[]},{"type":8,"value":[]}],"draft_materials_copied_info":[],"draft_name":"白大校花","draft_removable_storage_device":"","draft_root_path":"C:/Users/shiyi/AppData/Local/JianyingPro/User Data/Projects/com.lveditor.draft","draft_segment_extra_info":[],"draft_timeline_materials_size_":1601348115,"tm_draft_cloud_completed":"","tm_draft_cloud_modified":0,"tm_draft_create":1684288016841336,"tm_draft_modified":1684295080134819,"tm_duration":1240766666}';
        $array = json_decode($json, true);
        $array = var_export($array, true);
        return $array;
    }


}