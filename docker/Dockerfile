ARG PHP_BASE_IMAGE

FROM ${PHP_BASE_IMAGE}

RUN apt-get update && apt-get install -y \
    libtidy-dev \
    curl \
    git \
    gzip

RUN docker-php-ext-install tidy \
    && docker-php-ext-enable tidy

# Project folder
RUN mkdir -p /srv/app

WORKDIR /srv/app

RUN apt-get update \
    && apt-get install -y git zip curl gnupg \
    && rm -rf /var/lib/apt/lists/*

# get composer and required tools
RUN curl -sLO https://getcomposer.org/download/1.8.6/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Deployer
RUN curl -sLO https://deployer.org/releases/v6.4.3/deployer.phar \
    && mv deployer.phar /usr/local/bin/dep \
    && chmod +x /usr/local/bin/dep
