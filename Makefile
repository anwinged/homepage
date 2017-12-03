clear-files:
	rm -rf output_dev/*

build-site: clear-files
	./tools/sculpin generate --env=dev --no-interaction
	./tools/npm run build

build-docker:
	docker build --tag homepage-php .

watch:
	./tools/sculpin generate --env=dev --watch --server --port=8000

deploy: clear-files
	./tools/sculpin generate --env=prod --no-interaction
	./tools/npm run build-prod
	deployer deploy production

rollback:
	deployer rollback production

