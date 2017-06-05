<?php
include_once './vendor/autoload.php';
require_once("generated_proto/odcore_data_dmcp_ModuleDescriptor.php");
require_once("generated_proto/odcore_data_dmcp_RuntimeStatistic.php");
require_once("generated_proto/odcore_data_dmcp_ModuleStatistic.php");
require_once("generated_proto/odcore_data_dmcp_ModuleStatistics.php");
//=========================================//
// THE SETUP
if (false ===($tcpsock = stream_socket_server("tcp://127.0.0.1:8876",$errorno,$errstr)))
    echo"tcp socket failed : $errstr($errorno)\n";

if(false ===($connection = stream_socket_accept($tcpsock))) 
    echo "tcp accept failed!\n";

if (false ===($udpsock = stream_socket_server("udp://127.0.0.1:8876",$errorno,$errstr,STREAM_SERVER_BIND)))
    echo"$errstr($errorno)\n";

//=================================//
//messages

$UDP=array();
$TCP=array();
$data=array();

?>
<html><body><table style=width:100%>
<tr><th>UDP</th>
<th>TCP</th></tr>
<?php
$receivingData = true;
$dom=new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$dataReceived = $dom-> appendChild($dom->createElement("Data"));
$n = 0;

while($receivingData){
    $udpData = stream_socket_recvfrom($udpsock, 512, 0, $peer);
    $tcpData = fread($connection,512);

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