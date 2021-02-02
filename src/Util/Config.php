<?php
//+---------------------------------------------------------------------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+---------------------------------------------------------------------------------------------------------------------
//| Author:Janmas <janmas@126.com>
//+---------------------------------------------------------------------------------------------------------------------
//| 
//+---------------------------------------------------------------------------------------------------------------------


namespace Janmas\Sms\Util;

/**
 * Class Config
 * @package Janmas\Sms\Util
 */
class Config implements \ArrayAccess
{
	protected $config;
	/**
	 * @var self $instance
	 */
	public static $instance;

	public function __construct($config){
		$this->config = $config;
	}

	public static function setInstance($config){
		if(!self::$instance instanceof self){
			self::$instance = new self($config);
		}
		return self::$instance;
	}

	public static function instance(){
		return self::$instance;
	}

	/**
	 * @inheritDoc
	 */
	public function offsetExists( $offset )
	{
		return isset($this->config[$offset]);
	}

	/**
	 * @inheritDoc
	 */
	public function offsetGet( $offset )
	{
		if($this->offsetExists($offset)) return $this->config[$offset];
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function offsetSet( $offset, $value )
	{
		return $this->config[$offset] = $value;

	}

	/**
	 * @inheritDoc
	 */
	public function offsetUnset( $offset )
	{

	}

	public function set($key,$value){
		return $this->offsetSet($key, $value);
	}

	public function get($key){
		return $this->offsetGet($key);
	}

	public function all(){
		return $this->config;
	}
}