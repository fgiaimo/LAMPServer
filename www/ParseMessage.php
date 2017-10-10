<?php
class ParseMessage{
	//this fucntion converts the message from binary to hex.
	public function parse($bytes){
		$message = bin2hex($bytes);
		return $message;
	}

	public function getRawMessageLength($message){
		$lengthBytes =substr($message,4,10);
		return $lengthBytes; //return 650000
	}
	//returns the message used for proto. 
	public function getRawProtoMessage($message){
		$protoLength= $this -> getProtoLength($message);
		$rawProtoMessage = substr($message,10,$protoLength*2);
		return $rawProtoMessage;
	}
	//returns the opendavinci bytes
	public function getOpenDaVinciBytes($message){
		$openDaVinciBytes=substr($message,0,4);
		return $openDaVinciBytes; //return 0da4 
	}
	//big endian to little endian for 3 bytes.
	public function betole($lengthBytes){ //for the 3 bytes only
	    $firstByte= substr($lengthBytes,0,2); //every byte is a string
	    $secondByte= substr($lengthBytes,2,2);
	    $thirdByte= substr($lengthBytes,4,2);
	    $bigEnd= $thirdByte.$secondByte. $firstByte; //swap places

	    return $bigEnd;
	}
	//this function returns the expected length of the message used for proto. 
	public function getProtoLength($message){
		$lengthBytes = $this -> getRawMessageLength($message);
		$protoLengthmessage =$this -> betole($lengthBytes);
		$protoLength =hexdec($protoLengthmessage); //from hex to decimal
		return $protoLength;
	}
	//this fucntion compares the length of the message received, and the length that the string is expected to have. 
	public function checkContainer($message){
		$protoLength = $this ->getProtoLength($message);
		if (strlen($message) != $protoLength *2 +10){     // strlen(message) returns the length of every char, so 2 chars in a byte. 10 is the number of chars(5bytes) not considered(odbytes, length) in the expected length.              
        	$strlen = strlen($message);
        	$hexlength = $protoLength *2 +10;
        	echo "Wrong Length.\n Length of the message: ".$strlen ." not equal to expected length: ".$hexlength . "\n";

        	return false;
    	}
    	//check if the od bytes are correct
    	$odBytes = $this -> getOpenDaVinciBytes($message);
    	$bytesForOD = "0da4";
    	if ($odBytes != $bytesForOD ){
    		echo "odBytes: ". $odBytes;
    		echo "Wrong OD bytes\n";
    		return false;

    	}
    	return true;
	 }

	//this function is called once when the server starts. 
	public function createDomDocument(){
		$dom=new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom -> appendChild($dom->createElement("all_messages"));
		return $dom;
	}
	//once you start receiving data and saving it, all messages will be found in dom.xml. However, if you restart the server, it will start saving from the beginning again
	//this function is called every time we receive a message
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
			foreach ($message as $msg){ //for every message saved
			//every dom element has a tree structure, below we access an elements value
			$length = $msg -> childNodes ->item(0) -> nodeValue; 
			$odbytes = $msg -> childNodes -> item(1)-> nodeValue;
			$msgbytes = $msg -> childNodes -> item(2) -> nodeValue;
			
			echo "data: the length is: {$length} odbytes is:{$odbytes} and the msg bytes is: {$msgbytes}\n\n\n";
			flush ();
			
			
			}

	}

}
?>