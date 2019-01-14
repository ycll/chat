<?php
namespace app\index\controller;

use app\common\sms\ali\Sms;
use app\common\util\Tools;
use app\common\util\Redis;

class Login
{
	public function index() 
	{
		$phoneNum = trim($_GET['phone_num']);
		$code = trim($_GET['code']);
		// 从redis获得验证码的值。并且做验证（这里需要同步）
		$redis = Redis::getInstance();
		
		if ($redis->get_v('sms_'.$phoneNum) === $code){
			$data = [
				'phone_num' => $phoneNum,
				'is_login' => true,
			];
			
			$redis->set_v('user_'.$phoneNum, json_encode($data), 1200);

			return Tools::show(config('code.success'), 'success', $data);
		}
		return Tools::show(config('code.error'), 'login fail');
		
	} 
}
