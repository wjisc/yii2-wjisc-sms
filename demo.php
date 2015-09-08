<?php
/**
 * Created by PhpStorm.
 * User: hoter.zhang
 * Date: 2015/9/2
 * Time: 13:11
 */

$clientData = [
    'partner' => "20150902", //合作者id
    'key' => "testkey",// 秘鑰key
    'from' => "232767044", //發送號碼
    'to' => "232767044",//到達號碼
    'text' => "text",//發送內容
    'time' => '1111',//發送時間
    'sign_type' => 'md5', //默認、不修改
    'input_charset' => 'utf-8',//默認、不修改
    'transport' => 'http'//默認、不修改
];
$filter = paraFilter($clientData);//過濾空值元素  sign
$sort = argSort($filter); //重新排序
$prestr = createLinkString($sort); //創建 key=val& ... 鍵值對
$sin = md5Sign($prestr,$clientData['key']); //簽名驗證

$urlData = [
    'partner' => "20150902",
    'key' => "testkey",
    'from' => "232767044",
    'to' => "232767044",
    'text' => "text",
    'time' => '1111',
    'sign_type' => 'md5', //不修改
    'input_charset' => 'utf-8',//不修改
    'transport' => 'http',//不修改
    'sign' => $sin
];

$urlStr = createLinkString($urlData);
echo $requestUrl = "http://sms.com/index.php?r=api/sms&".$urlStr;
/***
    public function createLinkString($para) {
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

    public function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }
    public function md5Sign($preStr, $key) {
        $preStr = $preStr . $key;
        return md5($preStr);
    }
 ****/

/******************** 服務端 ****************************/
\Yii::$app->response->format = Response::FORMAT_JSON;
//第一步獲取用戶提交的partner
$partner = \Yii::$app->request->get('partner');
$key = \Yii::$app->request->get('key');
$sign = \Yii::$app->request->get('sign');
$from = \Yii::$app->request->get('from');
$to = \Yii::$app->request->get('to');
$text = \Yii::$app->request->get('text');
$time = \Yii::$app->request->get('time', time());


/*
 * 查詢數據庫操作
 * 1、partner是否存在？
 * return [
 *  'status' => '3',
 *  'message' => '用戶partner不存在'
 * ];
 * 2、驗證key是否正確？
 * return [
 *  'status' => '4',
 *  'message' => '授權key不正確'
 * ]
 */

//3 驗證sign是否正確
$config = [
    'partner' => $partner,
    'key' => $key,
];
$sms = new Sms($config);
$param = [
    'from' => $from,
    'to' => $to,
    'text' => $text,
    'time' => $time,
    'sign' => $sign
];

if ($sms->verifySing($param)) {
    $arr = [
        'form' => $from,
        'to' => $to,
        'text' =>$text,
        'time' => $time
    ];
    $ctm = new CtmSend($arr);
    if ($ctm->sendMsg()) {
        return [
            'status' => '1',
            'message' => '發送成功'
        ];
    } else {
        return [
            'status' => '0',
            'message' => '發送失敗'
        ];
    }
} else {
    return [
        'status' => '2',
        'message' => '簽名失敗'
    ];
}

//修改內容...