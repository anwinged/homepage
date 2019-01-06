build-docker:
	./tools/build-docker

clean-dev:
	rm -rf ./output_dev/*

clean-prod:
	rm -rf ./output_prod/*

build-assets-dev:
	./tools/npm run build

build-assets-prod:
	./tools/npm run build-prod

build-site-dev:
	./tools/sculpin generate \
		--env=dev \
		--no-interaction \
		-vv

build-site-prod:
	./tools/sculpin generate \
		--env=prod \
		--url="https://vakhrushev.me" \
		--no-interaction \
		-vv

build-dev: clean-dev build-assets-dev build-site-dev

build-prod: clean-prod build-assets-prod build-site-prod

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
