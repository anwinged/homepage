FROM php:7.2-cli

# Project folder
RUN mkdir -p /var/homepage

WORKDIR /srv/homepage

RUN apt-get update \
    && apt-get install -y git gnupg \
    && rm -rf /var/lib/apt/lists/*

# get composer and required tools
RUN curl -sLO https://getcomposer.org/download/1.5.2/composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Deployer
RUN curl -sLO https://deployer.org/releases/v6.0.3/deployer.phar \
    && mv deployer.phar /usr/local/bin/dep \
    && chmod +x /usr/local/bin/dep

# install npm
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*
