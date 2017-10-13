<?php

Class DisplayMessage{

	public function lastMessage(){
		$dom = new DOMDocument();
		$dom -> load('dom.xml');
		$message=$dom->getElementsByTagName('Message');
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
			foreach ($message as $msg){
				 // every dom element has a tree structure, to access the value this is how you do it.
				$length = $msg -> childNodes ->item(0) -> nodeValue;
				$odbytes = $msg -> childNodes -> item(1)-> nodeValue;
				$msgbytes = $msg -> childNodes -> item(2) -> nodeValue;
				
				echo "data: the length is: {$length} odbytes is:{$odbytes} and the msg bytes is: {$msgbytes}\n\n\n";
				flush (); //you need to flush it so that the string echoed can be streamed to the html file.
			
				
			}

	}
}


	// foreach ($UDPid as $UDPtext){
	// 	//$uID = $dom->getElementsByTagName('UDPid');
	// 	$udpid = $UDPtext -> nodeValue;
	// 	echo "the id is: {$udpid}";

	// }
	// foreach ($UDPmessage as $UDP){
	// 	//echo "UDP DATA".$UDP ->nodeValue ."\n\n";
		
	// 	$UDPmsg=$UDP ->item(0)-> nodeValue;
	// 	$udpID=$UDP -> item(1)-> nodeValue;
	// 	$UDPid=$dom ->getElementsByTagName('UDPid');
	// 	$uID=$UDPid ->item(0)->nodeValue;
		//$UDPnum++;
	// 	echo "data: The last message is:".$UDPmsg." and the num is: {$uID}\n\n";
	// 	//flush();
	// 	//sleep(1);
	// 	if ($uID>$UDPnum){
	// 		echo "flushed";
	// 		$UDPnum = $uID;
	// 		flush();
	// 	}
	// }

	//echo "in the lastMessage function";
	//return $UDPmsg;
//}


//echo "Last UDP id is: ".$UDPnum."\n";
//foreach ($TCPmessage as $TCP) {
	//echo "TCP DATA".$TCP ->nodeValue ."\n\n";
// 	$TCPnum++;
// }
//echo "Last TCP id is:".$TCPnum."\n";

?>