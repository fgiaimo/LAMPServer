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

$socket = new Sockets();
$parseMessage = new ParseMessage();
$protoClassContainer = new Container();

$tcpSocket = $socket -> createTcpSocket('tcp://127.0.0.1:8876');
$udpSocket = $socket -> createUdpSocket('udp://127.0.0.1:8876');

$tcpConn = $socket -> connectTcpSocket($tcpSocket);

$tcpMsg = $socket -> listenTcp($tcpConn);
$udpMsg = $socket ->listenUdp($udpSocket);

$connection = $socket -> getConnectionStatus($udpMsg,$tcpMsg);
//$dom = $parseMessage -> createDomDocument();

while($connection){ 
	//connection is always true, need to fix it. 
	$rawMessage= $socket -> listenUdp($udpSocket);
	$message = $parseMessage -> parse($rawMessage);

	if($parseMessage -> checkContainer($message)){
		//$parseMessage -> saveStreamXML($message,$dom);
		//Nice format of xml. Need to fix it.
		$hexedProtoMessage = $parseMessage -> getRawProtoMessage($message);
		$protoMessage = hex2bin($hexedProtoMessage);				//the protobuf message
		$protoClassContainer -> mergeFromString($protoMessage);					
		$serializedMessageFromProto = $protoClassContainer->serializeToString(); //the final string
		$bytes = $protoClassContainer -> getSerializedData();
        $moduleStatistics -> setModuleStatistics(bin2hex($bytes));
        echo "Bytes are : " . $bytes . "\n";
        echo "1st : " . $serializedMessageProto . "\n";
                


		echo "Message ok\n";
	}


}

?>