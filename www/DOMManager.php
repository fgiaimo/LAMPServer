<?php
class DOMManager{

    private $DOMFilename;
    private $DOM;
    
    public function __construct($filename = "DOM") {
        $this->DOMFilename=$filename.".xml";
        $this->createDOM();
    }
    
    protected function createDOM() {
        $this->DOM=new DOMDocument();
        $this->DOM->preserveWhiteSpace = false;
        $this->DOM->formatOutput = true;
        $this->DOM -> appendChild($this->DOM->createElement("OD4Messages"));
    }
    
    public function storeOD4Message($od4Message) {
        $od4MessageLength=$od4Message->getLength();
        $od4MessageRaw=$od4Message->getRawBytes();
        $domRoot=$this->DOM->getElementsByTagName("OD4Messages")->item(0);
        $domOD4Message = $domRoot->appendChild($this->DOM->createElement("OD4Message"));
        $result = $domOD4Message->appendChild($this->DOM->createElement("Length","$od4MessageLength"));
        $result = $domOD4Message->appendChild($this->DOM->createElement("Bytes","$od4MessageRaw"));
        $this->DOM -> save($this->DOMFilename);
//        echo "Message stored [".(int)$od4MessageLength." Bytes]: $od4MessageRaw \n\n";
    }
    
    public function lastMessage() {
        $this->DOM = new DOMDocument();
        $this->DOM->load($this->DOMFilename);
        $od4Messages=$this->DOM->getElementsByTagName("OD4Message");
            foreach ($od4Messages as $od4Message) {
                $od4MessageLength = $od4Message -> childNodes ->item(0) -> nodeValue; 
                $od4MessageRaw = $od4Message -> childNodes -> item(1)-> nodeValue;
//                echo "Message retrieved [".(int)$od4MessageLength." Bytes]: $od4MessageRaw \n\n";
                flush ();
            }
    }
}
?>

