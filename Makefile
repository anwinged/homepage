build-docker:
	./tools/build-docker

build-assets:
	./tools/npm run build

build-site:
	./tools/sculpin generate --clean --env=dev --no-interaction -vvv

build: build-site build-assets

build-prod:
	./tools/sculpin generate \
		--clean \
		--env=prod \
		--url="https://vakhrushev.me" \
		--no-interaction
	./tools/npm run build-prod

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
	./tools/dep deploy production

rollback:
	./tools/dep rollback production
