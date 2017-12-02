clean-files:
	rm -rf output_dev/*

build: clean-files
	./tools/sculpin generate --env=dev --no-interaction
	./tools/npm build

deploy: clean-files
	./tools/sculpin generate --env=prod --no-interaction
	./tools/npm build-prod
	deployer deploy production

rollback:
	deployer rollback production

docker-build:
	docker build --tag homepage-php .
