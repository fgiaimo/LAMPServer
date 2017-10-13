<?php
include_once './vendor/autoload.php';

require_once("Sockets.php");
require_once("ParseMessage.php");
require_once("generated_proto/GPBMetadata/ModuleStatistics.php");
require_once("generated_proto/ModuleDescriptor.php");
require_once("generated_proto/RuntimeStatistic.php");
require_once("generated_proto/ModuleStatistic.php");
require_once("generated_proto/ModuleStatistics.php");
require_once("generated_proto/Container.php");
require_once("generated_proto/TimePoint.php");

$socketsWrapper = new SocketsWrapper();
$messageParser = new MessageParser();
// $protoClassContainer = new Container();

$UDPSocket = $socketsWrapper -> createUDPSocket('udp://127.0.0.1:8877');

$TCPSocket = $socketsWrapper -> createTCPSocket('tcp://127.0.0.1:8876');
$TCPConnection = $socketsWrapper -> connectTCPSocket($TCPSocket);

$TCPData = $socketsWrapper -> receiveTCPData($TCPConnection); 
$UDPData = $socketsWrapper -> receiveUDPData($UDPSocket);

$DOM = $messageParser -> createDOMDocument(); // DOM file created

while(true) {
	$rawMessage = $socketsWrapper -> receiveUDPData($UDPSocket); 
	
	$message = $messageParser -> parse($rawMessage);
	if ($messageParser -> checkContainer($message)) { // sanity check
		$messageParser -> saveStreamXML($message,$DOM);
//        $serializedMessageFromProto = $protoClassContainer->serializeToString();
//        echo "\n proto msg: ". $serializedMessageFromProto;
        echo "Bytes received: $message\n";
    }
}
?>
