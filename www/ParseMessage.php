<?php
class ParseMessage{
//NOT TESTED
	public function parse($bytes){
		$message = bin2hex($bytes);
		return $message;
	}

	public function getRawMessageLength($message){
		$lengthBytes =substr($message,4,10);
		return $lengthBytes; //return 650000
	}
	public function getRawProtoMessage($message){
		$protoLength= $this -> getProtoLength($message);
		$rawProtoMessage = substr($message,10,$protoLength*2);
		return $rawProtoMessage;
	}
	
	public function getOpenDaVinciBytes($message){
		$openDaVinciBytes=substr($message,0,4);
		return $openDaVinciBytes; //return 0da4 
	}

	public function betole($lengthBytes){ //for the 3 bytes only
	    $firstByte= substr($lengthBytes,0,2);
	    $secondByte= substr($lengthBytes,2,2);
	    $thirdByte= substr($lengthBytes,4,2);
	    $bigEnd= $thirdByte.$secondByte. $firstByte;

	    return $bigEnd;
	}

	public function getProtoLength($message){
		$lengthBytes = $this -> getRawMessageLength($message);
		$protoLengthmessage =$this -> betole($lengthBytes);
		$protoLength =hexdec($protoLengthmessage);
		return $protoLength;
	}

	public function checkContainer($message){
		$protoLength = $this ->getProtoLength($message);
		if (strlen($message) != $protoLength *2 +10){                   //return if length is ok or not // needs the string as well
        	$strlen = strlen($message);
        	$hexlength = $protoLength *2 +10;
        	echo "Wrong Length.\n Strlen:".$strlen ."not equal to hexLength:".$hexlength . "\n";

        	return false;
    	}
    	
    	$odBytes = $this -> getOpenDaVinciBytes($message);
    	$bytesForOD = "0da4";
    	if ($odBytes != $bytesForOD ){
    		echo "odBytes: ". $odBytes;
    		echo "Wrong OD bytes\n";
    		return false;

    	}
    	return true;
	 }
	public function createDomDocument(){
		$dom=new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom -> appendChild($dom->createElement("all_messages"));
		return $dom;
	}



	public function saveStreamXML($message,$dom){
		$length=$this ->getRawMessageLength($message);
		$odBytes=$this ->getOpenDaVinciBytes($message);
		$protoMessage=$this ->getRawProtoMessage($message);
		$all_messages=$dom->getElementsByTagName('all_messages')->item(0);
		$dataReceived = $all_messages -> appendChild($dom->createElement("message"));
		$udpDataDOM = $dataReceived -> appendChild($dom->createElement('length',"$length"));
		$udpDataDOM = $dataReceived -> appendChild($dom->createElement('odBytes',"$odBytes"));
		$udpDataDOM = $dataReceived -> appendChild($dom->createElement('protoMessage',"$protoMessage"));

	    
	    $dom -> save("dom.xml");
	}
	public function lastMessage(){
		$dom = new DOMDocument();
		$dom -> load('dom.xml');
		$message=$dom->getElementsByTagName('Message');
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
			foreach ($message as $msg){
				//$uID = $dom->getElementsByTagName('UDPid');
				//$udpid = $UDPtext -> nodeValue;
				$length = $msg -> childNodes ->item(0) -> nodeValue;
				$odbytes = $msg -> childNodes -> item(1)-> nodeValue;
				$msgbytes = $msg -> childNodes -> item(2) -> nodeValue;
				//$udpid =$UDPtext ->childNodes ->item(1)-> nodeValue;
				//$udpdata= $UDPtext -> childNodes-> item(3)-> nodeValue;
				//if($udpid>$UDPnum){
				echo "data: the length is: {$length} odbytes is:{$odbytes} and the msg bytes is: {$msgbytes}\n\n\n";
				flush ();
				
				
			}

	}

}
?>