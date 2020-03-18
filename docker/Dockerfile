FROM php:7.4-cli

COPY ./docker/provision.sh /opt/

RUN /opt/provision.sh

WORKDIR /srv/app
