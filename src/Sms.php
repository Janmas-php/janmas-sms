<?php
//+---------------------------------------------------------------------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+---------------------------------------------------------------------------------------------------------------------
//| Author:Janmas <janmas@126.com>
//+---------------------------------------------------------------------------------------------------------------------
//| 
//+---------------------------------------------------------------------------------------------------------------------


namespace Janmas\Sms;


use Janmas\Sms\Drivers\{Ali, Yun};
use Janmas\Sms\Util\Config;

/**
 * Class Sms
 * @package Janmas\Sms
 * @property Ali|Yun $class
 */
class Sms
{
	private $class;

	public function __construct( string $driver, $config = [] )
	{
		$class = '\\Janmas\\Sms\\Drivers\\' . ucfirst($driver);
		if ( class_exists($class) ){
			$this->class = new $class(Config::setInstance($config));
		}
	}

	public function __call( $method, $argv )
	{
		if ( method_exists($this->class, $method) ){
			if ( count($argv) < 2 ){
				throw new \Exception('参数缺失');
			}
			return $this->class->$method($argv[0], $argv[1]);
		}
		throw new \Exception(sprintf('%s not found in %s', $method, get_class($this->class)));
	}
}