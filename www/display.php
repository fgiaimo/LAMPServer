<?php

$dom = new DOMDocument();
$dom -> load('dom.xml');
$TCPmessage=$dom->getElementsByTagName('TCPmessage');
$UDPmessage=$dom->getElementsByTagName('UDPmessage');
$UDPnum=0;
$TCPnum=0;
foreach ($UDPmessage as $UDP){
	echo "UDP DATA".$UDP ->nodeValue ."\n\n";
	$UDPnum++;
}
echo "Last UDP id is: ".$UDPnum."\n";
foreach ($TCPmessage as $TCP) {
	echo "TCP DATA".$TCP ->nodeValue ."\n\n";
	$TCPnum++;
}
echo "Last TCP id is:".$TCPnum."\n";

?>

