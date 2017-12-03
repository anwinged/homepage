build-assets:
	./tools/npm run build

build-site:
	./tools/sculpin generate --clean --env=dev --no-interaction

build-docker:
	docker build --tag homepage-php .

build: build-site build-assets

watch: build-assets
	./tools/sculpin generate \
		--env=dev \
		--watch \
		--server \
		--port=8000 \
		--no-interaction

deploy:
	./tools/npm run build-prod
	./tools/sculpin generate --clean --env=prod --no-interaction
	deployer deploy production

rollback:
	deployer rollback production
