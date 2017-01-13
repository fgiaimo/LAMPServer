#!/bin/bash

echo "if [ -f /etc/bash_completion ] && ! shopt -oq posix; then" >> /root/.bashrc
echo "    . /etc/bash_completion" >> /root/.bashrc
echo "fi" >> /root/.bashrc
source /root/.bashrc

service apache2 start

echo "Setup complete."

cd ~

/bin/bash
