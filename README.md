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

#### Installation:

1. Edit homestead.yml in the new directory vagrant_config and change the following lines:

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

2. Edit your host files C:\Windows\System32\drivers\etc and add:

    ```sh
    192.168.10.10 invetorysystem.app
    ```

3. Navigate with your console back to the inventorysystem directory and type in:

    ```sh
    vagrant up
    ```

4. Type in your terminal:

    ```sh
    vagrant ssh
    ```

5. Navigate with your console to:

    ```sh
    cd code/laravel
    ```

6. Migrate the database:

    ```sh
    php artisan migrate
    ```

7. Seed the database:

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