<?php
include_once './vendor/autoload.php';
require_once("generated_proto/GPBMetadata/ModuleStatistics.php");
require_once("generated_proto/ModuleDescriptor.php");
require_once("generated_proto/RuntimeStatistic.php");
require_once("generated_proto/ModuleStatistic.php");
require_once("generated_proto/ModuleStatistics.php");
require_once("generated_proto/Container.php");
require_once("generated_proto/TimePoint.php");
//=========================================//
// THE SETUP
if (false ===($tcpsock = stream_socket_server("tcp://127.0.0.1:8876",$errorno,$errstr)))
    echo"tcp socket failed : $errstr($errorno)\n";

if(false ===($connection = stream_socket_accept($tcpsock))) 
    echo "tcp accept failed!\n";
else echo "tcp accepted";

if (false ===($udpsock = stream_socket_server("udp://127.0.0.1:8876",$errorno,$errstr,STREAM_SERVER_BIND)))
    echo"$errstr($errorno)\n";

//=================================//
//messages

$UDP=array();
$TCP=array();
$data=array();


$receivingData = true;
$dom=new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$protoClassModuleStatistics = new ModuleStatistics();
$protoClassContainer = new Container();
$dataReceived = $dom-> appendChild($dom->createElement("Data"));
$n = 0;
$hex = array();
while($receivingData){
    $udpData = stream_socket_recvfrom($udpsock, 512, 0, $peer);
    $tcpData = fread($connection,512);
    $hex = bin2hex($tcpData); //hex the message
    echo $hex;
    $length = substr($hex,4,6);
    $firstByte= substr($hex,4,2);
    $secondByte= substr($hex,6,2);
    $thirdByte= substr($hex,8,2);
    $bigEnd= $thirdByte.$secondByte. $firstByte;
    $decLength= hexdec($bigEnd);
    
    echo "\n" . $decLength."\n". strlen($hex) ."\n";
    if (strlen($hex) != $decLength *2 +10){                   //return if length is ok or not // needs the string as well
        echo "Wrong Length \n";
    }
    $proto = hex2bin(substr($hex,10,$decLength*2));

    echo "Proto:" . $proto ."\n";
        echo bin2hex($proto);

         $protoClassContainer -> mergeFromString($proto);
        echo  "\n =========================== \n Proto says:". $protoClassContainer->serializeToString() ."\n";


    echo "--------parsed-----------";
    

    
    //    $moduleStats=$protoClass -> getModuleStatistics();
    

    $UDP[]=$udpData; 
    $TCP[]=$tcpData;

    $tcpEncodedData="";
    $udpEncodedData="";
    for ($i=0;$i<strlen($tcpData);$i++){
        $char_value=ord($tcpData[$i]);
        $tcpEncodedData .= $char_value;
        $tcpEncodedData .= " ";
    }
    for ($i=0;$i<strlen($udpData);$i++){
        $char_value=ord($udpData[$i]);
        $udpEncodedData .= $char_value;
        $udpEncodedData .= " ";
    }
    
    if($tcpData == -1){
        $receivingData = false;
        echo "Client off!\n";
    }
    $udpDataDOM = $dataReceived -> appendChild($dom->createElement("UDP"));
    $tcpDataDOM = $dataReceived -> appendChild($dom->createElement("TCP"));
    $tcpDataDOM -> appendChild($dom->createElement('TCPid',"$n"));
    $tcpDataDOM -> appendChild($dom->createElement('TCPmessage',"$tcpEncodedData"));
    $udpDataDOM -> appendChild($dom->createElement('UDPid',"$n"));
    $udpDataDOM -> appendChild($dom->createElement('UDPmessage',"$udpEncodedData"));
    $dom -> save("dom.xml");
    $n++;
    echo "Wrote data\n";
}

echo "</table></html></body>";
fclose($connection);
fclose($tcpsock);
fclose($udpsock);  
?>