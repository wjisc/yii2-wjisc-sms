<?php
/**
 * Created by PhpStorm.
 * User: hoter.zhang
 * Date: 2015/9/1
 * Time: 16:25
 */

namespace hoter\ctmsms;


class SmsCore {

    /**
     * “参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para
     * @return string
     */
    public static function createLinkString($para) {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg.=$key."=".$val."&";
        }
        //去除最後一個"&"
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        return $arg;
    }

    /**
     * 去掉請求參數為sign 或者 為空的元素
     * @param $para
     * @return array
     */
    public static function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 對數組進行重組排序處理
     * @param $para
     * @return mixed
     */
    public static function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

}