ARG PHP_BASE_IMAGE

FROM ${PHP_BASE_IMAGE}

# Project folder
RUN mkdir -p /srv/app

WORKDIR /srv/app

RUN apt-get update \
    && apt-get install -y git zip curl gnupg \
    && rm -rf /var/lib/apt/lists/*

# get composer and required tools
RUN curl -sLO https://getcomposer.org/download/1.6.5/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Deployer
RUN curl -sLO https://deployer.org/releases/v6.2.0/deployer.phar \
    && mv deployer.phar /usr/local/bin/dep \
    && chmod +x /usr/local/bin/dep
