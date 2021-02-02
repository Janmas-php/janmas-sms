<?php
//+---------------------------------------------------------------------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+---------------------------------------------------------------------------------------------------------------------
//| Author:Janmas <janmas@126.com>
//+---------------------------------------------------------------------------------------------------------------------
//| 
//+---------------------------------------------------------------------------------------------------------------------


namespace Janmas\Sms\Drivers;

use Janmas\Sms\Util\Request;

/**
 * 云片短信
 * @package Janmas\Sms\Drivers
 * @link []
 */
class Yun extends AbstractSmsDriver
{

	public function sendSms( $phone=[], $code=[] )
	{
		if ( $this->config->offsetExists('tpl_id') ){
			$apiUrl = 'https://sms.yunpian.com/v2/sms/tpl_single_send.json';
			$tpl_value = [];
			if (
				(is_array($this->config->get('tpl_value')) && !is_array($code)
				||
				(count($this->config->get('tpl_value')) != count($code)))
			){
				throw new \Exception('The number of arguments in ·code· does not match the number of arguments in ·tpl_value·');
			} else{
				foreach ($this->config->get('tpl_value') as $key => $value){
					$tpl_value[] = "#{$value}#={$code[$key]}";
				}
				$tpl_value = count($tpl_value) > 1 ? join('&', $tpl_value) : $tpl_value;
			}

			$query = [
				'tpl_id'    => $this->config->get('tpl_id'),
				'tpl_value' => urlencode($tpl_value),
				'apikey'    => $this->config->get('apikey'),
				'mobile'    => $phone
			];
		} else{
			$apiUrl = 'https://sms.yunpian.com/v2/sms/single_send.json';
			$query = [
				'text'   => $this->config->get('text') . $code,
				'apikey' => $this->config->get('apikey'),
				'mobile' => $phone,
			];
		}
		return $this->send($query, $apiUrl);
	}

	public function sendBatchSms( $phone = '', $code = '' )
	{
		if(
			(is_array($phone) && count($phone) > 200)
			||
			(is_string($phone) && substr_count($phone,','))
		){
			throw new \Exception('一次最多传200个手机号码');
		}
		$phone = is_array($phone)?join(',',$phone):$phone;

		if ( $this->config->offsetExists('tpl_id') ){
			$apiUrl = 'https://sms.yunpian.com/v2/sms/tpl_batch_send.json';
			$tpl_value = [];
			if (
				(is_array($this->config->get('tpl_value')) && !is_array($code)
				||
				(count($this->config->get('tpl_value')) != count($code)))
			){
				throw new \Exception('The number of arguments in ·code· does not match the number of arguments in ·tpl_value·');
			} else{
				foreach ($this->config->get('tpl_value') as $key => $value){
					$tpl_value[] = "#{$value}#={$code[$key]}";
				}
				$tpl_value = count($tpl_value) > 1 ? join('&', $tpl_value) : $tpl_value;
			}

			$query = [
				'tpl_id'    => $this->config->get('tpl_id'),
				'tpl_value' => urlencode($tpl_value),
				'apikey'    => $this->config->get('apikey'),
				'mobile'    => $phone
			];
		} else{
			$apiUrl = 'https://sms.yunpian.com/v2/sms/batch_send.json';
			$query = [
				'text'   => $this->config->get('text') . $code,
				'apikey' => $this->config->get('apikey'),
				'mobile' => $phone,
			];
		}

		return $this->send($query, $apiUrl);
	}

	private function send( $query, $url )
	{
		$header = [
			'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
			'Accept'       => 'application/json;charset=utf-8;'
		];
		$response = Request::instance()->method('post')->debug(false)->header($header)->url($url)->data($query)->exec();
		$response = json_decode($response);
		if ( $response->code != 0 ){
			throw new \Exception($response->detail);
		}
		return true;
	}
}