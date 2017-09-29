

<?php
require_once("getLastData.php");
include_once './vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Display implements MessageComponentInterface {
	private $clients;    

    public function __construct() 
    {    
        $this->clients = new \SplObjectStorage;
        $this->UDPmsg = lastMessage();
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        $this->clients->attach($conn);
        $conn -> send($this->UDPmsg);
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {            
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
                echo "msg sent to client";
            }
        }
    }

    public function onClose(ConnectionInterface $conn) 
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {     
        $conn->close();
    }
}

// $dom = new DOMDocument();
// $dom -> load('dom.xml');
// $TCPmessage=$dom->getElementsByTagName('TCPmessage');
// $UDPmessage=$dom->getElementsByTagName('UDPmessage');
// $UDPnum=0;
// $TCPnum=0;
// foreach ($UDPmessage as $UDP){
// 	echo "UDP DATA".$UDP ->nodeValue ."\n\n";
// 	$UDPnum++;
// }
// echo "Last UDP id is: ".$UDPnum."\n";
// foreach ($TCPmessage as $TCP) {
// 	echo "TCP DATA".$TCP ->nodeValue ."\n\n";
// 	$TCPnum++;
// }
// echo "Last TCP id is:".$TCPnum."\n";
?>
<!--  
=======
<?php

// $dom = new DOMDocument();
// $dom -> load('dom.xml');
// $TCPmessage=$dom->getElementsByTagName('TCPmessage');
// $UDPmessage=$dom->getElementsByTagName('UDPmessage');
// $UDPnum=0;
// $TCPnum=0;
// foreach ($UDPmessage as $UDP){
// 	echo "UDP DATA".$UDP ->nodeValue ."\n\n";
// 	$UDPnum++;
// }
// echo "Last UDP id is: ".$UDPnum."\n";
// foreach ($TCPmessage as $TCP) {
// 	echo "TCP DATA".$TCP ->nodeValue ."\n\n";
// 	$TCPnum++;
// }
// echo "Last TCP id is:".$TCPnum."\n";

?>
>>>>>>> d05103efe11123ff7e9f673e1bac38367afa2a8f
 --> 
