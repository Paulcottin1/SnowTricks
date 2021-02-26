FROM php:7.3-apache

WORKDIR /var/www/html

RUN if [ ! -z $http_proxy ] ; then pear config-set http_proxy $http_proxy; fi \
    && requirements="curl zip" \
    && apt-get -qq update \
    && apt-get install -y \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libmcrypt-dev \
            libpng-dev \
            libicu-dev \
            libpq-dev \
            libxpm-dev \
            libvpx-dev \
            zlib1g-dev \
            vim \
            wget \
            npm \
            gnupg \
            iputils-ping \
            libzip-dev \
            wkhtmltopdf\
    && apt-get install -qq -y ${requirements} \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) mbstring \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) pcntl \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) sockets \
    && apt-get purge --auto-remove -y

RUN npm install --global yarn

RUN pecl install apcu \
  && docker-php-ext-enable apcu

#install Imagemagick & PHP Imagick ext
RUN apt-get update && apt-get install -y \
        libmagickwand-dev --no-install-recommends
RUN pecl install imagick && docker-php-ext-enable imagick

## Start rewrite engine
RUN a2enmod rewrite
## Enable headers mod for Access-Control-Allow-Origin
RUN a2enmod headers
## Enable ssl
RUN a2enmod ssl

COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf
RUN service apache2 restart
COPY . /var/www/html

RUN cd /var/www/html && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-scripts

