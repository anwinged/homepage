build:
	rm -rf output_dev/*
	vendor/bin/sculpin generate --env=dev --no-interaction
	BUILD_ENV=dev node_modules/.bin/gulp build

deploy:
	rm -rf output_prod/*
	vendor/bin/sculpin generate --env=prod --no-interaction
	BUILD_ENV=prod node_modules/.bin/gulp build
	ansible-playbook --inventory "ansible/hosts_prod" --user=deployer ansible/deploy.yml

rollback:
	ansible-playbook --inventory "ansible/hosts_prod" --user=deployer ansible/rollback.yml

configure:
	ansible-playbook --inventory "ansible/hosts_prod" --user=av --ask-become-pass ansible/configuration.yml
