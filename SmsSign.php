<?php
/**
 * Created by PhpStorm.
 * User: hoter.zhang
 * Date: 2015/9/1
 * Time: 16:27
 */

namespace hoter\ctmsms;


class SmsSign {

    /**
     * 簽名字符串
     * @param $preStr
     * @param $key
     * @return string
     */
    public static function md5Sign($preStr, $key) {
        $preStr = $preStr . $key;
        return md5($preStr);
    }

    /**
     * 簽名驗證
     * @param $preStr
     * @param $sign
     * @param $key
     * @return bool
     */
    public static function md5Verify($preStr, $sign, $key) {
        $preStr = $preStr . $key;
        $mySign = md5($preStr);
        if($mySign == $sign) {
            return true;
        }
        else {
            return false;
        }
    }
}