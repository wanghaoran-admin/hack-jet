<?php

namespace app\index\service;

class SrtText
{


    public static function getInfo($file)
    {
        $content = file($file);
        //var_dump($content);exit();
        foreach ($content as $key => $value) {
            if (empty(trim($value))) {
                unset($content[$key]);
            } else {
                $res[] = trim($value);
            }
        }
        $content = array_chunk($res, 3);
        foreach ($content as $key => &$value) {
            $value['id'] = $value[0];
            unset($content[$key][0]);
            $value['time'] = $value[1];
            $time = explode('-->', $value['time']);
            $value['start'] = trim($time[0]);
            $value['end'] = trim($time[1]);
            unset($content[$key][1]);
            unset($value['time']);
            $value['text'] = trim($value[2]);
            unset($content[$key][2]);
            $value['sub'][] = $value;
            $value['sub'][0]['id'] = 0;
            unset($value['start']);
            unset($value['end']);
            unset($value['text']);
            $result1[] = $value;

        }
        
        return $result1;
    }
}