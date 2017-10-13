#!/bin/bash
echo "Removing docker containers"
docker stop $(docker ps -a -q) ; docker rm $(docker ps -a -q)
echo "Building and starting image"
docker build -t php_server . && docker run -t -i --network=host php_server 
