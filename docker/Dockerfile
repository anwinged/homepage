FROM php:7.3-cli

RUN apt-get update && apt-get install -y \
    libtidy-dev \
    curl \
    git \
    gzip \
    rsync

RUN docker-php-ext-install tidy \
    && docker-php-ext-enable tidy

# Project folder
RUN mkdir -p /srv/app

WORKDIR /srv/app

RUN apt-get update \
    && apt-get install -y git zip curl gnupg \
    && rm -rf /var/lib/apt/lists/*

# get composer and required tools
RUN curl -sLO https://getcomposer.org/download/1.9.3/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Deployer
RUN curl -sLO https://deployer.org/releases/v6.7.3/deployer.phar \
    && mv deployer.phar /usr/local/bin/dep \
    && chmod +x /usr/local/bin/dep
