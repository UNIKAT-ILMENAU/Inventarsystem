Vagrant.configure(2) do |config|

  config.vm.box = "debian/contrib-jessie64"

  config.vm.network "forwarded_port", guest: 80,   host: 8000
  config.vm.network "forwarded_port", guest: 81,   host: 8001

  config.vm.provider "virtualbox" do |vb|
     vb.memory = "1024"
     vb.cpus = 2
   end

   config.vm.synced_folder ".", "/vagrant",
       id: "vagrant-root",
       owner: "vagrant",
       group: "www-data",
       mount_options: ["dmode=775,fmode=664"]

   # provisioning
   config.vm.provision "ansible_local" do |ansible|
     ansible.playbook = "./vagrant/vagrant-playbook.yml"
   end
end
