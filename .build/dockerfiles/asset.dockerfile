FROM node:22

RUN npm install -g npm@11.1.0

WORKDIR /var/www/html

ENTRYPOINT [ "tail", "-f", "/dev/null" ]