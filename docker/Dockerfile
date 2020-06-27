FROM php:7.4.7-cli

COPY ./docker/provision.sh /opt/

RUN /opt/provision.sh

WORKDIR /srv/app
