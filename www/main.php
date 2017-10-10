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

$tcpSocket = $socket -> createTcpSocket('tcp://127.0.0.1:8876'); //this is where you give the address, tcp is for a tcp socket and needs to be written. 
$udpSocket = $socket -> createUdpSocket('udp://127.0.0.1:8876'); // Udp is for udp socket and needs to be written. 

$tcpConn = $socket -> connectTcpSocket($tcpSocket); //only tcp socket needs to connect, no need for the udp one.

//storing the messages
$tcpMsg = $socket -> listenTcp($tcpConn); 
$udpMsg = $socket ->listenUdp($udpSocket);


$connection = $socket -> getConnectionStatus($udpMsg,$tcpMsg);//ensure that we have a valid connection
$dom = $parseMessage -> createDomDocument(); // a dom file is created, every time the file starts, a new file creates. 

while($connection){
	$rawMessage= $socket -> listenUdp($udpSocket); 
	$message = $parseMessage -> parse($rawMessage);// we need the data in hexadecimal 

	if ($parseMessage -> checkContainer($message)){ //checking if we have a corrupted message
		$parseMessage -> saveStreamXML($message,$dom);
		
        $serializedMessageFromProto = $protoClassContainer->serializeToString(); // we use protocol buffers to serialize the data.

        echo "\n proto msg: ". $serializedMessageFromProto;

        echo "Message ok\n";
    }
}

?>