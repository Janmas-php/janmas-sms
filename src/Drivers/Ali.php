<?php
//+---------------------------------------------------------------------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+---------------------------------------------------------------------------------------------------------------------
//| Author:Janmas <janmas@126.com>
//+---------------------------------------------------------------------------------------------------------------------
//| 
//+---------------------------------------------------------------------------------------------------------------------


namespace Janmas\Sms\Drivers;


use AlibabaCloud\Client\AlibabaCloud;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

/**
 * 阿里短信
 * @package Janmas\Sms\Drivers
 * @link [https://help.aliyun.com/document_detail/101414.html?spm=a2c4g.11186623.6.625.1caf1a23lFh3FT] [发送单条]
 * @link [https://help.aliyun.com/document_detail/102364.html?spm=a2c4g.11186623.6.626.28e15d67it5QbW] [发送多条]
 */
class Ali extends AbstractSmsDriver
{

	public function sendSms( $phone='', $code='' )
	{
		$query = [
			'RegionId' => $this->config->get('regionId')??'cn-hangzhou',
			'PhoneNumbers' => $phone,
			'SignName' => $this->config->get('signName'),
			'TemplateCode' => $this->config->get('templateCode'),
			'TemplateParam' => json_encode([$this->config->get('templateParam')=>$code])
		];

		return $this->send($query,'SendSms');
	}

	public function sendBatchSms( $phone = [], $code = [] )
	{
		if(!is_array($phone) || !is_array($code)){
			throw new \Exception('参数只能是数组');
		}else if(count($phone) != count($code)){
			throw new \Exception('手机号码与验证码数量不一致');
		}
		$sendCode = [];
		foreach($code as $key=>$value){
			$sendCode[$this->config->get('templateParam')] = $value;
		}

		$query = [
			'RegionId' => $this->config->get('regionId')??'cn-hangzhou',
			'PhoneNumbers' => json_encode($phone),
			'SignName' => json_encode(array_fill(0, count($phone)-1, $this->config->get('signName'))),
			'TemplateCode' => $this->config->get('templateCode'),
			'TemplateParam' => json_encode($code)
		];
		return $this->send($query,'SendBatchSms');
	}

	private function send($query,$scene){
		AlibabaCloud::accessKeyClient($this->config->get('accessKeyId'),$this->config->get('accessSecret'))
			->regionId('cn-hangzhou')
			->asDefaultClient();

		try {
			$result = AlibabaCloud::rpc()
				->product('Dysmsapi')
				->scheme('https') // https | http
				->version('2017-05-25')
				->action($scene)
				->method('POST')
				->host('dysmsapi.aliyuncs.com')
				->options([
					'query' => $query
				])
				->request();
			return $result->toArray();
		} catch (ClientException $e) {
			throw new \Exception($e->getMessage());
		} catch (ServerException $e) {
			throw new \Exception($e->getMessage());
		}
	}

}