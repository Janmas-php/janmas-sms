<?php
//+---------------------------------------------------------------------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+---------------------------------------------------------------------------------------------------------------------
//| Author:Janmas <janmas@126.com>
//+---------------------------------------------------------------------------------------------------------------------
//| 
//+---------------------------------------------------------------------------------------------------------------------


namespace Janmas\Sms\Drivers;


use Janmas\Sms\Util\Config;

abstract class AbstractSmsDriver
{
	protected $config = [];

	public function __construct(Config $config){
		$this->config = $config;
	}

	abstract public function sendSms($phone,$code);

	abstract public function sendBatchSms($phone=[],$code=[]);


}