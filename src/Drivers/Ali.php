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

	/**
	 * 发送短息
	 * @param string $phone
	 * @param string $code
	 * @return array
	 * @throws \Exception
	 */
	public function sendSms( $phone='', $code='' )
	{
		$query = [
			'RegionId' => $this->config->get('regionId')??'cn-hangzhou',
			'PhoneNumbers' => $phone,
			'SignName' => $this->config->get('signName'),
			'TemplateCode' => $this->config->get('templateCode'),
		];
//			'TemplateParam' => json_encode([$this->config->get('templateParam')=>$code])
        if(is_array($code)){
            $query['TemplateParam'] = json_encode($code,JSON_UNESCAPED_UNICODE);
        }else{
            $key = $this->config->offsetExists('templateParam')?$this->config->get('templateParam'):'code';
            $query['TemplateParam'] = json_encode([$key=>$code]);
        }
		return $this->send($query,'SendSms');
	}

	/**
	 * 发送多条短信
	 * @param array $phone
	 * @param array $code
	 * @return array
	 * @throws \Exception
	 */
	public function sendBatchSms( $phone = [], $code = [] )
	{
		if(!is_array($phone) || !is_array($code)){
			throw new \Exception('参数只能是数组');
		}else if(count($phone) != count($code)){
			throw new \Exception('手机号码与验证码数量不一致');
		}

		$query = [
			'RegionId' => !$this->config->offsetExists('regionId')?'cn-hangzhou':$this->config->get('regionId'),
			'PhoneNumberJson' => json_encode($phone),
			'SignNameJson' => json_encode(array_fill(0, count($phone), $this->config->get('signName'))),
			'TemplateCode' => $this->config->get('templateCode'),
			'TemplateParamJson' => json_encode($code,JSON_UNESCAPED_UNICODE)
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
			$result = $result->toArray();
			if($result['Code'] != 'OK'){
                throw new \Exception($result['Message']);
            }
			return $result;
		} catch (ClientException $e) {
			throw new \Exception($e->getMessage());
		} catch (ServerException $e) {
			throw new \Exception($e->getMessage());
		}
	}

}