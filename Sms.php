<?php
/**
 * Created by PhpStorm.
 * User: hoter.zhang
 * Date: 2015/9/1
 * Time: 16:41
 */

namespace hoter\ctmsms;


class Sms {

    /**
     * 1 接受用戶請求參數
     * 2 根據用戶的partner_id 查詢數據庫 獲得對應數據（partner,key,sign_type,input_charset,transport） + 用戶提交的的from+to+text
     * 3 然後把組合的數據提交通過實例化傳過來
     * 4 組合參數---》 加密 ---》 與用戶提交的sign對比
     * 5 發送短信 返回狀態
     */
    private $partner;
    private $key;
    private $sign_type = "md5";
    private $input_charset = "utf-8";
    private $transport = "http";


    /**
     * 實例化 需要傳入partner, key2個參數
     * @param $config
     */
    public function __construct($config){
        $this->partner = (isset($config['partner'])&&$config['partner']!="") ?  $config['partner'] : '';
        $this->key = (isset($config['key'])&&$config['key']!="") ?  $config['key'] : '';
    }

    /**
     * 驗證用戶的sign是否正確
     * $params = [
     *      'from'=>'',
     *      'to' => '',
     *      'text' => '',
     *      'time' => '',
     *      'sign' => ''
     * ];
     *
     * @param $params
     * @return bool
     */
    public function verifySing($params) {
        $params['partner'] = $this->partner;
        $params['key'] = $this->key;
        $params['sign_type'] = $this->sign_type;
        $params['input_charset'] = $this->input_charset;
        $params['transport'] = $this->transport;
        //1 過濾 空值元素 sing
        $paramsFilter = SmsCore::paraFilter($params);
        $paramsSort = SmsCore::argSort($paramsFilter);
        return SmsSign::md5Verify($paramsSort, $params['sign'], $this->key);
    }


}