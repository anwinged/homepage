APP_ENV := dev
APP_OUTPUT_DIR := output_dev
APP_URL := homepage.site
APP_NPM_BUILD_CMD := build

ifeq ($(TARGET), prod)
	APP_ENV := prod
	APP_OUTPUT_DIR := output_prod
	APP_URL := https://vakhrushev.me
	APP_NPM_BUILD_CMD := build-prod
endif

# Installation

install: build-docker install-php-deps install-js-deps

build-docker:
	./tools/build-docker

install-php-deps:
	./tools/composer install --no-interaction

install-js-deps:
	./tools/npm ci

# Building

clean:
	rm -rf ./${APP_OUTPUT_DIR}/*

build-assets:
	./tools/npm run "${APP_NPM_BUILD_CMD}"

build-site:
	./tools/sculpin generate \
		--env="${APP_ENV}" \
		--url="${APP_URL}" \
		--no-interaction \
		-vv

build: clean build-assets build-site

build-prod:
	$(MAKE) build TARGET=prod

# Format

format-pages:
	./tools/npm run format-md

format-assets:
	./tools/npm run format-webpack
	./tools/npm run format-js
	./tools/npm run format-vue
	./tools/npm run format-style

format-php:
	./tools/php-cs-fixer fix

format: format-pages format-assets format-php

watch: clean build-assets
	./tools/sculpin generate \
		--env="${APP_ENV}" \
		--watch \
		--server \
		--port=8000 \
		--no-interaction

# Deploy

deploy: build-prod
	./tools/build-and-deploy-in-prod

rollback:
	./tools/dep rollback production -vv
