deploy:
	vendor/bin/sculpin generate --env=prod
	ansible-playbook --inventory 'anwinged.ru,' --user=deployer ansible/deploy.yml

rollback:
	ansible-playbook --inventory 'anwinged.ru,' --user=deployer ansible/rollback.yml
