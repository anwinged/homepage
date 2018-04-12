build-docker:
	./tools/build

build-assets:
	./tools/npm run build

build-site:
	./tools/sculpin generate --clean --env=dev --no-interaction

build: build-site build-assets

watch: build-assets
	./tools/sculpin generate \
		--env=dev \
		--watch \
		--server \
		--port=8000 \
		--no-interaction

deploy:
	./tools/sculpin generate --clean --env=prod --no-interaction
	./tools/npm run build-prod
	deployer deploy production

rollback:
	deployer rollback production
