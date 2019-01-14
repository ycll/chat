<?php
$http = new Swoole\WebSocket\Server("0.0.0.0", 9501);
$http->listen('0.0.0.0', 9502, SWOOLE_SOCK_TCP);


$http->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});


$http->on('request', function ($request, $response) {
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});

$http->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});


$http->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});




$http->start();