FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    gzip \
    iputils-ping \
    && apt-get clean

RUN docker-php-ext-install gd zip

ENV INFORMIXDIR=/opt/ibm/informix
ENV INFORMIXSERVER=informix
ENV INFORMIXSQLHOSTS=/opt/ibm/informix/etc/sqlhosts

RUN mkdir -p $INFORMIXDIR

COPY .data/informix_sdk_installed.tar.gz /tmp/

RUN tar -xzf /tmp/informix_sdk_installed.tar.gz -C /opt/ibm && \
    rm /tmp/informix_sdk_installed.tar.gz

ENV LD_LIBRARY_PATH="$LD_LIBRARY_PATH:$INFORMIXDIR/lib:$INFORMIXDIR/lib/cli:$INFORMIXDIR/lib/esql"

COPY  .data/pdo_informix.so /usr/local/lib/php/extensions/no-debug-non-zts-20220829

RUN echo "extension=pdo_informix.so" > /usr/local/etc/php/conf.d/pdo_informix.ini

RUN echo "informix onsoctcp informix 9088" > /opt/ibm/informix/etc/sqlhosts

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]