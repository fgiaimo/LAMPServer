<?php
include_once './vendor/autoload.php';

require_once("generated_proto/GPBMetadata/ModuleStatistics.php");
require_once("generated_proto/ModuleDescriptor.php");
require_once("generated_proto/RuntimeStatistic.php");
require_once("generated_proto/ModuleStatistic.php");
require_once("generated_proto/ModuleStatistics.php");
require_once("generated_proto/Container.php");
require_once("generated_proto/TimePoint.php");

require_once("SocketsWrapper.php");
require_once("DOMManager.php");
require_once("OD4Message.php");

$socketsWrapper = new SocketsWrapper();
$domManager = new DOMManager("od4messages");

$UDPSocket = $socketsWrapper -> createUDPSocket('udp://127.0.0.1:8877');

$TCPSocket = $socketsWrapper -> createTCPSocket('tcp://127.0.0.1:8876');
$TCPConnection = $socketsWrapper -> connectTCPSocket($TCPSocket);

$TCPData = $socketsWrapper -> receiveTCPData($TCPConnection); 
$UDPData = $socketsWrapper -> receiveUDPData($UDPSocket);

while(true) {
	$rawMessage = $socketsWrapper -> receiveUDPData($UDPSocket); 
	
	$od4Message = new OD4Message($rawMessage);
	
	if ($od4Message->isValid()) { // sanity check
        echo "[main] Valid message received\n";
		$domManager -> storeOD4Message($od4Message);
		try {
    		$container = new Container();
            $container->mergeFromString($od4Message->getPayload());
            echo "[main] Container successfully decoded\n";
        } catch (Exception $e) {
            echo "[main] Protobuf parsing exception: $e\n";
        }
    } else {
        echo "[main] Received corrupted data [".(string)$rawMessage."]\n";
    }
}
?>
