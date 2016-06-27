# Vagrant for UNIKAT Invetorysystem

## 1. Installation


### Setup Requirements:
- VirtualBox
- Vagrant
- you need SSH Keys for vagrant ssh
    https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/

#### Common Errors:
- if there is a error while the vagrant box is starting (vagrant up) please edit
  .vagrant/machines/default/virtualbox/creator_uid in for example 0
- you should have ssh keys to communicate with the vagrant box

#### Windows:
1. Add the Vagrant Box to Windows with:

    ```sh
    vagrant box add laravel/homestead
    ```


2. Navigate in your cmd to the directory you want to create the inventorysystem. 
Next we clone the Git repository with:
    ```sh
    git clone PFAD_ZU_DEM_GIT inventorysystem
    ```

3. Run the init.bat with: (not necessary in the latest version)
    ```sh
    init.bat
    ```

4. Open the Vagrantfile and change the following line:
    ```sh
    confDir = $confDir ||= File.expand_path("YOUR_PATH_TO_INVENTORYSYSTEM/vagrant_config")
    ```

5. Edit homestead.yml in the new directory vagrant_config and change the following lines:

    ```sh
    ip: "192.168.10.10"
    memory: 2048
    cpus: 1
    provider: virtualbox
    
    #Your SSH Public Key
    authorize: /Users/YOUR_NAME/.ssh/id_rsa.pub
    
    #Your SSH Private Key
    keys:
        - /Users/YOUR_NAME/.ssh/id_rsa

    #Your Project Folder
    folders:
        - map: /Users/YOUR_NAME/Desktop/Inventorysystem/code
        to: /home/vagrant/code

    #Your Host
    sites:
        - map: inventorysystem.app
        to: /Users/YOUR_NAME/Desktop/Inventorysystem/code/public

    ```


6. Edit your host files C:\Windows\System32\drivers\etc and add:
    ```sh
    192.168.10.10 invetorysystem.app
    ```

7. Navigate with your console back to the inventorysystem directory and type in:
    ```sh
    vagrant up
    ```
8. Type in your terminal:
    ```sh
    vagrant ssh
    ```
9. Navigate with your console to:
    ```sh
    cd code/laravel
    ```
10. Migrate the database:
    ```sh
    php artisan migrate
    ```
11. Seed the database:
    ```sh
    php artisan db:seed
    ```
### Linux and Mac

The same like in Windows 
1. Add the Vagrant Box to Windows with:

    ```sh
    vagrant box add laravel/homestead
    ```


2. Navigate in your cmd to the directory you want to create the inventorysystem. 
Next we clone the Git repository with:
    ```sh
    git clone PFAD_ZU_DEM_GIT inventorysystem
    ```

3. Run the init.sh with: (not necessary in the latest version)
    ```sh
    bash init.sh
    ```

4. Open the Vagrantfile and change the following line:
    ```sh
    confDir = $confDir ||= File.expand_path("YOUR_PATH_TO_INVENTORYSYSTEM/vagrant_config")
    ```

5. Edit homestead.yml in the new directory vagrant_config and change the following lines:

    ```sh
    ip: "192.168.10.10"
    memory: 2048
    cpus: 1
    provider: virtualbox
    
    #Your SSH Public Key
    authorize: /home/USERNAME/.ssh/id_rsa.pub
    
    #Your SSH Private Key
    keys:
        - /home/USERNAME/.ssh/id_rsa

    #Your Project Folder
    folders:
        - map: /home/USERNAME/Inventorysystem/code
        to: /home/vagrant/code

    #Your Host
    sites:
        - map: inventorysystem.app
        to: /home/USERNAME/Inventorysystem/code/public

    ```


6. Edit your host files etc/hosts and add:
    ```sh
    192.168.10.10 invetorysystem.app
    ```

7. Navigate with your console back to the inventorysystem directory and type in:
    ```sh
    vagrant up
    ```
8. Type in your terminal:
    ```sh
    vagrant ssh
    ```
9. Navigate with your console to:
    ```sh
    cd code/laravel
    ```
10. Migrate the database:
    ```sh
    php artisan migrate
    ```
11. Seed the database:
    ```sh
    php artisan db:seed
    ```
### Directory Stuff

The current directory configuartion looks like:

```sh
---code
------laravel
---vagrant
------vagrant-config
```