deploy:
	vendor/bin/sculpin generate --env=prod
	ansible-playbook --inventory "ansible/hosts_prod" --user=deployer ansible/deploy.yml

rollback:
	ansible-playbook --inventory "ansible/hosts_prod" --user=deployer ansible/rollback.yml

configure:
	ansible-playbook --inventory "ansible/hosts_prod" --user=av --ask-become-pass ansible/configuration.yml
