<?php
//+---------------------------------------------------------------------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+---------------------------------------------------------------------------------------------------------------------
//| Author:Janmas <janmas@126.com>
//+---------------------------------------------------------------------------------------------------------------------
//| 云片的短信
//+---------------------------------------------------------------------------------------------------------------------


include '../../vendor/autoload.php';

use Janmas\Sms\Sms;

$config = [
	'apikey' => '*******',
	'text' => '********'
];

$sms = new Sms('yun',$config);
/**
 * 发送单条
 * @var \Janmas\Sms\Drivers\Yun $sms
 */
//$res = $sms->sendSms('15890161317','');
//var_dump($res);
//exit;
#============================================分割线========================================================
$config = [
	'apikey' => '',
	'tpl_id' => '***',
	'tpl_value' => '',
];

$sms = new Sms('yun',$config);
/**
 * 指定模板单条发送
 * @var \Janmas\Sms\Drivers\Yun $sms
 */
$res = $sms->sendSms('15890161317','123456');
var_dump($res);
exit;
#============================================分割线========================================================
$config = [
	'apikey' => '',
	'text' => '【云片网】您的验证码是'
];

$sms = new Sms('yun',$config);
/**
 * 批量发送
 * @var \Janmas\Sms\Drivers\Yun $sms
 */
$sms->sendBatchSms('***********','******');

$config = [
	'apikey' => '',
	'tpl_id' => '3359436',
	'tpl_value' => ['code','template'],

];
#============================================分割线========================================================
$sms = new Sms('yun',$config);
/**
 * 指定模板批量发送
 * @var \Janmas\Sms\Drivers\Yun $sms
 */
$sms->sendBatchSms('***********','******');