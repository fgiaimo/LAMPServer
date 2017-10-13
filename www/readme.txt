This file will be located in /var/www/html and this is where all the scripts to run the php side are located. 

unused/display.html

This file is supposed to listen to a php file (getLastData.php). The EventSource is a browser built in function. This is the easiest way to make the website display the feed in real time. However, it isnt fixed, the only way i made it work was when getLastData.php didnt have a class.(not working)

unused/display.php

This is a file that implements functions from ratchet library(socketo.me). There are many benifits in using this library as it opens a web socket communication where clients can communicate with each other and the server as well. Due to the lack of documentation for the server-client communication i chose to use the EventSource. (not working)

unused/Dom.php

This was used to test getLastData.php so that i can fix display.html

unused/getLastData.php

This file reads from dom.xml and it streams the data to an html file. 

unused/index.php

This is the old file when there was no functional code and it contains nearly everything. It can still be used and it works but would recommend the other part(main.php).

main.php

This file creates sockets, and handles the protocol buffers. 

ParseMessage.php

This file handles the messages but not the protocol buffers. 

Sockets.php 

This file handles the sockets.

unused/test.php

Same as main.php, but was mainly used for testing. 

unused/webSocketsServer.php

This is a side server which opens a tcp communication channel for streaming data live. It is used mostly for communication between clients (e.g, chat). (not working) 
