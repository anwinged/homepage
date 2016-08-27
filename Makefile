deploy-test:
	ansible-playbook --inventory ansible/hosts-vagrant ansible/deploy.yml

deploy-prod:
	ansible-playbook --inventory ansible/hosts-eos --ask-become-pass ansible/deploy.yml

setup-test:
	ansible-playbook --inventory ansible/hosts-vagrant ansible/setup.yml

setup-prod:
	ansible-playbook --inventory ansible/hosts-eos --ask-become-pass ansible/setup.yml
