<?php
/**
 * Created by PhpStorm.
 * User: hoter.zhang
 * Date: 2015/9/1
 * Time: 17:10
 */

namespace hoter\ctmsms;


class CtmSend {

    private $postUrl = "http://202.75.248.226/HttpSend/HttpSendAction";
    private $copid = "1888";
    private $username = "abc";
    private $password = "123";
    private $time;
    private $from;
    private $to;
    private $text;

    public function __construct($arr) {
        $this->from = (isset($arr['from'])&&$arr['from']!='') ? $arr['from'] : '';
        $this->to = (isset($arr['to'])&&$arr['to']!='') ? $arr['to'] : '';
        $this->text = (isset($arr['text'])&&$arr['text']!='') ? $arr['text'] : '';
        $this->time = (isset($arr['time'])&&$arr['time']!='') ? $arr['time'] : time();
    }

    /**
     * 通過平台發送
     * @return bool
     */
    public function sendMsg() {
        $sendData = [
            'copid' => $this->copid,
            'username' => $this->username,
            'password' => $this->password,
            'from' => $this->from,
            'to' => $this->to,
            'text' => $this->text,
            'time' => $this->time
        ];
        $response = $this->httpRespnosePost($this->postUrl, $sendData);
        if ($response) {
            return true;
        } else {
            return false;
        }
    }

    public function httpRespnosePost($url,$para) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        //curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
        return $responseText;
    }

}