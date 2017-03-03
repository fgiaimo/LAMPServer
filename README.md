Custom LAMP server image
========================

Info:
------------------------

Based on tutum/lamp

Includes PHP 5 and MySQL (currently disabled).

To start the server, run `sh runLAMPserver.sh`

Ports used:
* Port 80 (accepting local connections) for visualization purposes
* Port 54321 for data exchange (run `telnet localhost 54321` in the terminal to test)


Scripts:
------------------------

* <b>runLAMPserver.sh</b> fires up the docker image containing the server itself.
* <b>startup.sh</b> is the server startup script, it is run when the image starts and activates the server inside the image.
