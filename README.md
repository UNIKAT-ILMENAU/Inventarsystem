# UNIKAT inventory system

## About this project

This project was created as a software project at the Ilmenau University of Technology. Its goal is to develop an inventory system for "UNIKAT", which is a FabLab organized by students. The inventory system has 2 main functions: The first one is to provide a database that contains all devices and materials belonging to UNIKAT as well as their current location and state. In addition to that, the inventory system is capable of creating and managing rentals of items, making it much easier to keep track of who rented something, when he rented it and also when he has to give it back.

## Installation


### Setup Requirements:
- VirtualBox
- Vagrant
- you need SSH Keys for vagrant ssh
    https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/

#### Common Errors:
- if there is an error while starting the vagrant box (vagrant up), please edit:
  .vagrant/machines/default/virtualbox/creator_uid in for example 0
- you should have ssh keys to communicate with the vagrant box

#### Installation:

1. Edit homestead.yaml in the new directory vagrant_config and change the following lines:

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
        - map: YOUR_PATH/code
        to: /home/vagrant/code

    #Your Host
    sites:
        - map: inventorysystem.app
        to: /home/vagrant/code/laravel/public

    ```

2. Edit your host files C:\Windows\System32\drivers\etc and add:

    ```sh
    192.168.10.10 inventorysystem.app
    ```

3. Open your terminal and type in:

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
