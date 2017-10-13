<?php
class SocketsWrapper {
    public function createTCPSocket($url) {
        echo " * Creating TCP socket...\n";
        $TCPSocket = stream_socket_server($url, $errno, $errstr);
        if ($TCPSocket === false){
            echo " * TCP socket creation failed [$errstr ($errno)]\n";
        }
        echo " * TCP socket creation successful\n";
        return $TCPSocket;
    }
    
    public function connectTCPSocket($TCPSocket) {
        echo " * Connecting TCP socket...\n";
        $TCPConnection = stream_socket_accept($TCPSocket);
        if ($TCPConnection === false){
            echo " * TCP socket connection failed\n";
//            return 0;
        }
        echo " * TCP socket connection successful\n";
        return $TCPConnection;
    }

    public function receiveTCPData($TCPConnection) {
        $TCPData = fread($TCPConnection, 512);
        while ($TCPData and $TCPData != -1){
            return $TCPData;
        }   
        return 0;
    }

    public function createUDPSocket($url) {
        echo " * Creating UDP socket...\n";
        $UDPSocket = stream_socket_server($url, $errno, $errstr, STREAM_SERVER_BIND); 
        if ($UDPSocket === false) {
            echo " * UDP socket creation failed [$errstr ($errno)]\n";
//            return 0;
        }
        echo " * UDP socket creation successful\n";
        return $UDPSocket;
    }

    public function receiveUDPData($UDPSocket) {
        $UDPData = stream_socket_recvfrom($UDPSocket, 512, 0, $peer);
        while ($UDPData != -1) {
            return $UDPData;
        }    
        return 0;
    }
}
