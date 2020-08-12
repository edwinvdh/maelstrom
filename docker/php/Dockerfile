# Custom php build based on php 7.4.4

FROM php:7.4.4-fpm

LABEL maintainer="Edwin vd Heide"

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
    git \
    libzmq3-dev \
    g++ \
    libbz2-dev \
    libc-client-dev \
    libcurl4-gnutls-dev \
    libedit-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libkrb5-dev \
    libldap2-dev \
    libldb-dev \
    libmagickwand-dev \
    libmcrypt-dev \
    libmemcached-dev \
    libpng-dev \
    libpq-dev \
    libsqlite3-dev \
    libssl-dev \
    libreadline-dev \
    libxslt1-dev \
#    libzip-dev \
#    wget \
#    unzip \
#    zlib1g-dev \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    bz2 \
    calendar \
    exif \
    gettext \
    mysqli \
    opcache \
    pdo_mysql \
    xmlrpc \
    xsl \
    && wget https://cs.symfony.com/download/php-cs-fixer-v2.phar -O php-cs-fixer \
    && sudo chmod a+x php-cs-fixer \
    && sudo mv php-cs-fixer /usr/local/bin/php-cs-fixer \
    && PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install -j$(nproc) imap \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && pecl install mongodb && docker-php-ext-enable mongodb \
	&& pecl install apcu && docker-php-ext-enable apcu \
    && docker-php-source delete \
#    && apt-get remove -y g++ wget \
    && apt-get remove -y g++ \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*