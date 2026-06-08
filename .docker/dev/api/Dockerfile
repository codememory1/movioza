FROM php:8.4.14-fpm

ARG workdir

WORKDIR $workdir

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash

RUN apt-get update && apt-get install -y \
    curl \
    git \
    wget \
    unzip \
    libcurl4-openssl-dev \
    libzip-dev \
    libxml2-dev \
    libicu-dev \
    build-essential \
    libonig-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    libpq-dev \
    bash  \
    bash-doc  \
    bash-completion \
    htop

# DB driver
RUN docker-php-ext-install pdo pdo_pgsql pcntl

RUN docker-php-ext-install zip xml intl mbstring curl
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp-1.11.0

RUN docker-php-ext-configure gd \
  --with-jpeg \
  --with-freetype

RUN docker-php-ext-install bcmath gd opcache

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet
RUN composer global require bamarni/symfony-console-autocomplete
RUN chmod 0777 ./ -R

CMD ["php-fpm"]