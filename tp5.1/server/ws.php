<?php

class http {
	public $http = null;


	public function __construct()
	{
		$this->http = new swoole_websocket_server('0.0.0.0', 9503);
		//$this->http->listen('0.0.0.0', 9502, SWOOLE_SOCK_TCP);

		$this->http->set([

			'enable_static_handler' => true,
			'document_root' => '/home/swoole/study/code/tp5.1/public/static',
			'worker_num' => 4,
			'task_worker_num' => 4,
//'pen_websocket_close_frame' =>true,
			]);
		
		$this->http->on('open',[$this, 'onOpen']);
		$this->http->on('close',[$this, 'onClose']);
		$this->http->on('message', [$this, 'onMessage']);
		$this->http->on('WorkerStart', [$this, 'onWorkerStart']);
		$this->http->on('request', [$this, 'onRequest']);
		$this->http->on('Task', [$this, 'onTask']);
		$this->http->on('Finish', [$this, 'onFinish']);

		$this->http->start();


	}

	public function onOpen($server, $request){
		//echo $request->fd;		
// 存入redis
		app\common\util\Redis::getInstance()->sadd(config('redis.live_game_key'), $request->fd);
		echo $request->fd, "\n";
		echo 'hello ws'."\n";
	}
	
	public function onClose($server, $fd){
		// 删除redis
		app\common\util\Redis::getInstance()->srem(config('redis.live_game_key'), $fd);
		echo 'thanks ws'."\n";
	}
	public function onMessage($server, $frame){
		echo 'hello message';
	}

	public function onTask(swoole_server $serv, int $task_id, int $src_worker_id, $data) {
		$method = $data['method'];
		$task = app\common\util\Task::$method($data['phone'], $data['code']);	
	}

	public function onFinish() {
		echo 'Finish task';
	}

	public function onWorkerStart(swoole_server $server, $worker_id) {
		// 定义应用目录
			define('APP_PATH', __DIR__ . '/../application/');
			// 加载基础文件
			require __DIR__ . '/../thinkphp/start.php';
	}

	public function onRequest($request, $response) {
		$_SERVER = [];
		
		if (!empty($request->server))
		{
			foreach($request->server as $k => $v)
			{
				$_SERVER[strtoupper($k)] = $v;	
			}
		}

		$_GET = [];
//		$_GET['http_s'] = $this->http;
		if (!empty($request->get))
		{
			foreach($request->get as $k => $v)
			{
				$_GET[$k] = $v;	
			}
		}
		$_FILES = [];
		if (!empty($request->files))
		{
			foreach ($request->files as $k => $v)
			{
				$_FILES[$k] = $v;
			}
		}

//		 var_dump($_GET);

		$_POST = [];
		
		if (!empty($request->post))
		{
			foreach($request->post as $k => $v)
			{
				$_POST[$k] = $v;	
			}
		}
		$_POST['http'] = $this->http;

		// 执行应用并响应
		ob_start();
		try {
		think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
	    	->run()
	    	->send();
		}catch(\Exception $e)
		{
//:w
			echo $e->getMessage();
		}

		$res = ob_get_contents();
		ob_end_clean();
		$response->end($res);
	}
	



}

new http();	
