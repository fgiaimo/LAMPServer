FROM tutum/lamp:latest

RUN apt-get update && apt-get install -y vim telnet

RUN mkdir /var/default-app && mv /var/www/html/* /var/default-app
ADD www /var/www/html

EXPOSE 80 54321

# For Apache setup: CMD ["/run.sh"]

ADD startup.sh /startup.sh
RUN chmod +x /startup.sh

CMD /bin/bash /startup.sh

