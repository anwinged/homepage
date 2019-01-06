build-docker:
	./tools/build-docker

build-assets:
	./tools/npm run build

build-assets-prod:
	./tools/npm run build-prod

build-site:
	./tools/sculpin generate \
		--clean \
		--env=dev \
		--no-interaction \
		-vv

build-site-prod:
	./tools/sculpin generate \
		--clean \
		--env=prod \
		--url="https://vakhrushev.me" \
		--no-interaction \
		-vv

build-dev: build-assets build-site

build-prod: build-assets-prod build-site-prod

format:
	./tools/npm run format-webpack
	./tools/npm run format-js
	./tools/npm run format-vue
	./tools/npm run format-style

format-php:
	./tools/php-cs-fixer fix

watch: build-assets
	./tools/sculpin generate \
		--env=dev \
		--watch \
		--server \
		--port=8000 \
		--no-interaction

deploy: build-prod
	./tools/dep deploy production -vv

rollback:
	./tools/dep rollback production -vv
