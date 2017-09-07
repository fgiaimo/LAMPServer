<?php
// include_once './vendor/autoload.php';
// require_once("generated_proto/GPBMetadata/ModuleStatistics.php");
// require_once("generated_proto/ModuleDescriptor.php");
// require_once("generated_proto/RuntimeStatistic.php");
// require_once("generated_proto/ModuleStatistic.php");
// require_once("generated_proto/ModuleStatistics.php");
// require_once("generated_proto/Container.php")


class Sockets{
	public function createTcpSocket($address){
		$tcpsock = stream_socket_server($address,$errorno,$errstr);
		if ($tcpsock===false){
			echo "TCP socket failed";
		}
		return $tcpsock;
	}
	public function connectTcpSocket($tcpsock){
		$connection = stream_socket_accept($tcpsock);
		if ($connection===false){
			echo "TCP connection failed";
            return 0;
		}
		return $connection;
	}

    public function listenTcp($connection){
        $tcpData = fread($connection,512);
        while ($tcpData != -1){
            return $tcpData;
        }   
        return 0;
    }

	public function createUdpSocket($address){
		$udpsock = stream_socket_server($address,$errorno,$errstr,STREAM_SERVER_BIND);
		if ($udpsock===false){
			echo "UDP connection failed";
            return 0;
		}
        return $udpsock;
	}

	public function listenUdp($udpsock){
		$udpData = stream_socket_recvfrom($udpsock, 512, 0, $peer);
		while ($udpData != -1){
			return $udpData;
		}	
		return 0;
	}
    //check this and fix it
    public function getConnectionStatus($udpData,$tcpData){
        if (($udpData == -1)and($tcpData == -1)){
            return false;
        }else
        return true;
    }
}
