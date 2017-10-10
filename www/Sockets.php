<?php

class Sockets{
	public function createTcpSocket($address){ //creates a tcp socket
		$tcpsock = stream_socket_server($address,$errorno,$errstr);
		if ($tcpsock===false){
			echo "TCP socket failed";
		}
		return $tcpsock;
	}
	public function connectTcpSocket($tcpsock){ //connects a tcp socket
		$connection = stream_socket_accept($tcpsock);
		if ($connection===false){
			echo "TCP connection failed";
            return 0;
		}
		return $connection;
	}

    public function listenTcp($connection){ //reads the received data
        $tcpData = fread($connection,512);
        while ($tcpData != -1){
            return $tcpData;
        }   
        return 0;
    }

	public function createUdpSocket($address){//creates a udp socket
		$udpsock = stream_socket_server($address,$errorno,$errstr,STREAM_SERVER_BIND);//stream_server_bind is required as an option for udp sockets.
		if ($udpsock===false){
			echo "UDP connection failed";
            return 0;
		}
        return $udpsock;
	}

	public function listenUdp($udpsock){//reads the received data 
		$udpData = stream_socket_recvfrom($udpsock, 512, 0, $peer);
		while ($udpData != -1){
			return $udpData;
		}	
		return 0;
	}
    //need to fix
    public function getConnectionStatus($udpData,$tcpData){
        if (($udpData == -1)and($tcpData == -1)){
            return false;
        }else
        return true;
    }
}
