<?php
class OD4Message{

    private $rawBytes;
    private $header;
    private $hexLength;
    private $length;
    private $payload;

    public function __construct($bytes) {
        $this->rawBytes = bin2hex($bytes);
        settype($this->rawBytes, "string");
        $this->header = substr($this->rawBytes,0,4);
        settype($this->header, "string");
        $this->parseLength(substr($this->rawBytes,4,10));
        $this->payload = substr($this->rawBytes,10);
        settype($this->payload, "string");
    }

    // PROBABLY INCORRECT
    protected function parseLength($string) {
        $firstByte = substr($string,0,2);
        $secondByte = substr($string,2,2);
        $thirdByte = substr($string,4,2);
        $fourthByte = "00";
        $this->hexLength = $fourthByte.$thirdByte.$secondByte.$firstByte;
        settype($this->hexLength, "integer");
        $this->length = hexdec($this->hexLength);
        settype($this->length, "integer");
    }

    public function isValid() {
        return ($this->isHeaderValid() && $this->isLengthValid());
    }
    
    public function isHeaderValid() {
        return ($this->header == "0da4");
    }
    
    public function isLengthValid() {
        return (strlen($this->payload)/2 == $this->length);
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function getPayload() {
        return $this->payload;
    }
    
    public function getRawBytes() {
        return $this->rawBytes;
    }
    
//    public function getHexLength() {
//        return $this->hexLength;
//    }
}
?>
