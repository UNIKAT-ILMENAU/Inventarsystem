Vagrant.configure(2) do |config|

  config.vm.box = "debian/contrib-jessie64"

  config.vm.network "forwarded_port", guest: 80,   host: 8000

  config.vm.provider "virtualbox" do |vb|
     vb.memory = "1024"
     vb.cpus = 2
   end

   # provisioning
   config.vm.provision "ansible" do |ansible|
     ansible.sudo = true
     ansible.playbook = "./vagrant/vagrant-playbook.yml"
   end
end
