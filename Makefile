build-docker:
	./tools/build

build-assets:
	./tools/npm run build

build-site:
	./tools/sculpin generate --clean --env=dev --no-interaction

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
	./tools/npm run format-md

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
