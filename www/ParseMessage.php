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
		
		return $dom;
	}



	public function saveStreamXML($message,$dom){
		$length=$this ->getRawMessageLength($message);
		$odBytes=$this ->getOpenDaVinciBytes($message);
		$protoMessage=$this ->getRawProtoMessage($message);
		$dataReceived = $dom-> appendChild($dom->createElement("message"));
		$udpDataDOM = $dataReceived -> appendChild($dom->createElement('length',"$length"));
		$udpDataDOM = $dataReceived -> appendChild($dom->createElement('odBytes',"$odBytes"));
		$udpDataDOM = $dataReceived -> appendChild($dom->createElement('protoMessage',"$protoMessage"));

	    
	    $dom -> save("dom.xml");
	}

}
?>