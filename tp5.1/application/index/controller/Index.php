<?php
namespace app\index\controller;

use app\common\sms\ali\Sms;

class Index
{
	    public function index()
	    {
//		return '1212';
	    }

	    public function hello($name = 'ThinkPHP5')
    		{
        		return 'hello,' . $name;
   	 	}

	public function sms()
	{
		$sms = new Sms();
		
		return $sms->sendSms('18512830328', '666666');

	}

   
}
