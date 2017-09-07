Custom LAMP server image
========================

Info:
------------------------

Based on [tutum/lamp](https://hub.docker.com/r/tutum/lamp/)

Includes PHP 5 and MySQL (currently disabled).

To start the server, run `sh runLAMPserver.sh`

To display the received data, run `php display.php`

To modify the installation commands(composer,protoc) modify `startup.sh`


------------------------

`index.php:`
This is the file that handles the TCP/UDP connections,receives the messages and encode them, and saves them to a file (dom.xml).

`/var/www/html/Proto:`
This is where protoc will look for the proto files, which will then generate the php classes to `/var/www/html/generated_proto`

`vendor:`
When composer installs something it saves the library here

---------------------------

Ports used:
* Port 80 (accepting local connections) for visualization purposes
* Port 54321 for data exchange (run `telnet localhost 54321` in the terminal to test)


Scripts:
------------------------

* <b>runLAMPserver.sh</b> starts up the docker container based on an image containing ubuntu trusty (14.04) and the needed PHP and MySQL dependencies.
* <b>startup.sh</b> is the server startup script, which is executed when the docker container is run to start the Apache server.
* <b>phpSettings.sh</b> Ignore this script
