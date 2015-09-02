yii2-wjisc-sms
==============
yii2-wjisc-sms

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hoter/yii2-wjisc-sms:dev-master
```

or add

```
"hoter/yii2-wjisc-sms": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
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