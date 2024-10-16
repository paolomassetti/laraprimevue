FROM node:20

WORKDIR /var/www/html

ENTRYPOINT [ "tail", "-f", "/dev/null" ]