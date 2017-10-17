<?php
include_once './vendor/autoload.php';

require_once("SocketsWrapper.php");
require_once("ParseMessage.php");
require_once("generated_proto/GPBMetadata/ModuleStatistics.php");
require_once("generated_proto/ModuleDescriptor.php");
require_once("generated_proto/RuntimeStatistic.php");
require_once("generated_proto/ModuleStatistic.php");
require_once("generated_proto/ModuleStatistics.php");
require_once("generated_proto/Container.php");
require_once("generated_proto/TimePoint.php");


require_once("OD4Message.php");

$socketsWrapper = new SocketsWrapper();
$messageParser = new MessageParser();

$UDPSocket = $socketsWrapper -> createUDPSocket('udp://127.0.0.1:8877');

$TCPSocket = $socketsWrapper -> createTCPSocket('tcp://127.0.0.1:8876');
$TCPConnection = $socketsWrapper -> connectTCPSocket($TCPSocket);

$TCPData = $socketsWrapper -> receiveTCPData($TCPConnection); 
$UDPData = $socketsWrapper -> receiveUDPData($UDPSocket);

$DOM = $messageParser -> createDOMDocument();

while(true) {
	$rawMessage = $socketsWrapper -> receiveUDPData($UDPSocket); 
	
	$od4Message = new OD4Message($rawMessage);
	
	if ($od4Message->isValid()) { // sanity check
		$messageParser -> saveStreamXML($od4Message->getRawBytes(),$DOM);
        echo "Valid message: ".$od4Message->getRawBytes()."\n";
    } else {
        echo "Received corrupted data [".(string)$rawMessage."]\n";
    }
}
?>
