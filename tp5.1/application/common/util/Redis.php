<?php

namespace app\common\util;


// 单例模式的redis
class Redis {
	// 实例化的对象
	private $redis = null;
	private static $instance = null;
	// 私有的构造函数
	private function __construct(){
	$this->redis = new \Redis();
	$this->redis->connect(config('redis.host'), config('redis.port'), 2);
}
	public static function getInstance() {
		if (empty(self::$instance)){
			self::$instance = new Redis();
		}
		return self::$instance;
}

	public function get_v($key)
	{
		return $this->redis->get($key);
	
	}

	public function set_v($key, $value, $time_out)
	{
		return $this->redis->setex($key, $time_out, $value);
	}

	public function sadd($key, $value) {
		return $this->redis->sadd($key, $value);
	}

	public function srem($key, $value) {
		return $this->redis->srem($key, $value);
	}
	
	public function smembers($key) {
		return $this->redis->smembers($key);
	}
}
