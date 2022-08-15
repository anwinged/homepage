#!/usr/bin/env sh

set -eux

apt-get update && apt-get install -y \
    curl \
    git \
    gnupg \
    gzip \
    libtidy-dev \
    rsync \
    zip \
;

docker-php-ext-install tidy \
    && docker-php-ext-enable tidy

# Project folder
mkdir -p /srv/app

# Composer and required tools
curl -sLO https://getcomposer.org/download/2.3.10/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Deployer
curl -sLO https://deployer.org/releases/v6.8.0/deployer.phar \
    && mv deployer.phar /usr/local/bin/dep \
    && chmod +x /usr/local/bin/dep
