# -*- mode: ruby -*-
# vi: set ft=ruby :

ENV["LC_ALL"] = "en_US.UTF-8"

# For installing ansible_local from pip on guest
Vagrant.require_version ">= 1.8.3"

Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/trusty64"

  config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "ansible/vagrant-provision.yml"
    ansible.galaxy_role_file = "ansible/vagrant-requirements.yml"
    ansible.galaxy_roles_path = "ansible/galaxy.roles"
    ansible.sudo = true
  end

  config.ssh.forward_agent = true
end
