FROM php:7.4.21-cli

COPY ./docker/provision.sh /opt/

RUN /opt/provision.sh

WORKDIR /srv/app
