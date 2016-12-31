deploy:
	vendor/bin/sculpin generate --env=prod
	ansible-playbook --inventory 'anwinged.ru,' --user=av --ask-become-pass ansible/deploy.yml
