# UNIKAT inventory system

## About this project

This project was created as a software project at the Ilmenau University of Technology. Its goal is to develop an inventory system for "UNIKAT", which is a FabLab organized by students. The inventory system has 2 main functions: The first one is to provide a database that contains all devices and materials belonging to UNIKAT as well as their current location and state. In addition to that, the inventory system is capable of creating and managing rentals of items, making it much easier to keep track of who rented something, when he rented it and also when he has to give it back.

### Developer

Kevin Bartsch
Maximilian Rütz
Franz Stecher
Martin Werchan
Oliver Sommer
Connor Schellhorn
David Schulz
Andreas Wehenkel

## Installation


### Setup Requirements:
- VirtualBox
- Vagrant

#### Common Errors:
- if there is an error while starting the vagrant box (vagrant up), please edit:
  .vagrant/machines/default/virtualbox/creator_uid in for example 0

#### Installation:

1. Open your terminal, navigate to your repository and type in:

    ```sh
    vagrant up
    ```

2. Type in your terminal:

    ```sh
    vagrant ssh
    ```

3. Navigate with your console to:

    ```sh
    cd code/laravel
    ```

4. Migrate the database:

    ```sh
    php artisan migrate
    ```

5. Seed the database:

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

