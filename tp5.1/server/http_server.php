<?php
// 实例化
$http = new swoole_http_server('0.0.0.0', 9503);

$http->set([

'enable_static_handler' => true,
'document_root' => '/home/swoole/study/code/tp5.1/public/static',

]);

$http->on('WorkerStart', function(swoole_server $server, $worker_id) {
	// 定义应用目录
	define('APP_PATH', __DIR__ . '/../application/');
	// 加载基础文件
	require __DIR__ . '/../thinkphp/base.php';

});

$http->on('request', function($request, $response) use ($http){
	$_SERVER = [];
	
	if (!empty($request->server))
	{
		foreach($request->server as $k => $v)
		{
			$_SERVER[strtoupper($k)] = $v;	
		}
	}

	$_GET = [];	

	if (!empty($request->get))
	{
		foreach($request->get as $k => $v)
		{
			$_GET[$k] = $v;	
		}
	}

	// var_dump($_GET);

	$_POST = [];
	
	if (!empty($request->post))
	{
		foreach($request->post as $k => $v)
		{
			$_POST[$k] = $v;	
		}
	}
//	var_dump($_SERVER);

	// 执行应用并响应
	ob_start();
	try {
	think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
    	->run()
    	->send();
	}catch(\Exception $e)
	{
	}
//	echo '--action--:', request()->action();
	$res = ob_get_contents();
	ob_end_clean();
	$response->end($res);
	// $http->close();
});

$http->start();
