<?php
//+---------------------------------------------------------------------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+---------------------------------------------------------------------------------------------------------------------
//| Author:Janmas <janmas@126.com>
//+---------------------------------------------------------------------------------------------------------------------
//| 阿里的短信
//+---------------------------------------------------------------------------------------------------------------------

include '../../vendor/autoload.php';

use Janmas\Sms\Sms;

$config = [
	'accessKeyId' => '*********',
	'accessSecret' => '*********',
	'signName' => '******',
	'templateCode' => '******',
];

$sms = new Sms('ali',$config);

/**
 * 发送单条(多变量)
 * @var \Janmas\Sms\Drivers\Ali $sms
 */
//$res = $sms->sendSms('***********',['code'=>'132456','address'=>'asdasdasd','phone'=>'1589016131']);

/*$config = [
    'accessKeyId' => 'LTAI6xls0Blbdf5g',
    'accessSecret' => 'oEFoMf00tLT7fwl20uT0bUsznFtRaG',
    'signName' => 'SuiYi',
    'templateCode' => 'SMS_190794346',
    'templateParam' => 'code',
];*/

/**
 * 发送单条(单变量)
 * @var \Janmas\Sms\Drivers\Ali $sms
 */
//$res = $sms->sendSms('***********','123456');

/**
 *
 * 发送多条 多变量
 */
//$sms->sendBatchSms(['***********'],[['code'=>'132456','address'=>'asdasdasd','phone'=>'1589016131']]);

$sms->sendBatchSms(['***********'],['code'=>'****']);