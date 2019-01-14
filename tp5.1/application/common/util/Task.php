<?php

namespace app\common\util;
use app\common\sms\ali\Sms;
  

class Task {
	public static function sendSms ($phoneNum, $code) {
		$sms = new Sms();
		$sms->sendSms($phoneNum, $code);
}

}
