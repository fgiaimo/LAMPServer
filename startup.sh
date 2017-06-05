#!/bin/bash

echo "if [ -f /etc/bash_completion ] && ! shopt -oq posix; then" >> /root/.bashrc
echo "    . /etc/bash_completion" >> /root/.bashrc
echo "fi" >> /root/.bashrc
source /root/.bashrc

service apache2 start

echo "Apache Started."



# cd /var/protobuf/ext/google/protobuf
# pear package 
# pecl install protobuf.tgz

cd /etc/php5/apache2 && sh phpSettings.sh 
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
cd /var/protoc/bin && ./protoc --php_out=/var/www/html/generated_proto /var/www/html/proto/ModuleStatistics.proto --proto_path=/var/www/html/proto 
cd /var/www/html
php /usr/local/bin/composer require "google/protobuf"
php /usr/local/bin/composer require "cboden/ratchet"
/bin/bash
