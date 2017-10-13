<?php
use \Display;
include_once './vendor/autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
//require_once ("display.php");
$server = IoServer::factory(
    new WsServer(
        new Display()
    )
    , 8080
);
echo "ws Server started";
$server->run();
echo "ws server done \n";
?>