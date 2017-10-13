FROM php:7.1-fpm 												
RUN pecl install xdebug-2.5.0 \
    && docker-php-ext-enable xdebug
#FROM php:5.6-fpm
#RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev \
#    && pecl install memcached-2.2.0 \
#    && docker-php-ext-enable memcached

#FROM tutum/lamp:latest

RUN apt-get update && apt-get install -y vim telnet curl git zip unzip 
RUN mkdir /var/default-app && bash -c '[ -d /var/www/html/ ] && mv /var/www/html/ /var/default-app'
ADD www /var/www/html

RUN mkdir /var/protoc
ADD protoc /var/protoc

RUN mkdir /var/www/html/proto
ADD proto /var/www/html/proto

RUN mkdir /var/www/html/generated_proto
EXPOSE 8876 8877

# For Apache setup: CMD ["/run.sh"]

ADD startup.sh /startup.sh
ADD phpSettings.sh /etc/php5/apache2/phpSettings.sh

RUN chmod +x /startup.sh

CMD /bin/bash /startup.sh

