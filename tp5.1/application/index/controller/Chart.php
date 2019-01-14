<?php
namespace app\index\controller;

use app\common\sms\ali\Sms;
use app\common\util\Tools;
use app\common\util\Redis;

class Chart
{
	public function index() {
	$http = $_POST['http'];

	$user = Redis::getInstance()->smembers('live_game_key');

	foreach($user as $fd){
	$data = [
		'type'=>'chart',
		'data'=>$_POST['content']
	];
	$http->push($fd, json_encode($data));
}	
}

//echo "当前服务器共有 ".count($http->connections). " 个连接\n";
}
