build:
	rm -rf output_dev/*
	vendor/bin/sculpin generate --env=dev --no-interaction
	BUILD_ENV=dev node_modules/.bin/gulp build

deploy:
	rm -rf output_prod/*
	vendor/bin/sculpin generate --env=prod --no-interaction
	BUILD_ENV=prod node_modules/.bin/gulp build
	deployer deploy production

rollback:
	deployer rollback production
