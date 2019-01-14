<?php
namespace app\index\controller;

use app\common\sms\ali\Sms;
use app\common\util\Tools;

class Send
{
	public function index() 
	{
		$phoneNum = trim(request()->get('phone_num', 0, 'intval'));
		if (empty($phoneNum))
		{
//			$tools = new Tools();
//			return $tools->show(config('code.error'), 'error');
			return Tools::show(config('code.error'), 'It happends error');
		}

		// 生成二维码
		$code = mt_rand(1000, 9999);
		
		// 发短信放入task
		$http = $_POST['http'];
		$data = [
			'method' => 'sendSms',
			'phone' => $phoneNum,
			'code' => $code,
		];
		$http->task($data);
//		$sms = new Sms();
//		$res = $sms->sendSms($phoneNum, $code);
//		$res = 'hello redis';

		// 存入redis
		$redis = new \Swoole\Coroutine\Redis();
		$redis->connect(config('redis.host'), config('redis.port'));
		$redis->set('sms_'.$phoneNum, $code, 120);		

//		$redis->start();		

		return Tools::show(config('code.success'), '发生了短信发送操作', [$code]);
		
	} 
}
