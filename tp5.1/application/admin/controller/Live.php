<?php

namespace app\admin\controller;

use app\common\util\Tools;
use app\common\util\Redis;

class Live {

	public function push() {
		$http = $_POST['http'];
		// 获取链接用户
		$user = Redis::getInstance()->smembers(config('redis.live_game_key'));
		
		$teams = [
			1 => [
				'name' => '马刺',
				'logo' => '/live/imgs/team1.png',
			],
			4 => [
                                  'name' => '火箭',
                                  'logo' => '/live/imgs/team2.png',
                          ],
		];
		
		$data = [
			'type' => intval($_GET['type']),
			'title' => !empty($teams[$_GET['team_id']]['name']) ? $teams[$_GET['team_id']]['name'] : '直播员',
			'logo' =>  !empty($teams[$_GET['team_id']]['logo']) ? $teams[$_GET['team_id']]['logo'] : '',
			'content' => !empty($_GET['content']) ? $_GET['content'] : '',
			'image' => !empty($_GET['image']) ? $_GET['image'] : '',
		
		];

		foreach ($user as $fd){
			$http->push($fd, json_encode($data));
		}	

	}


}
