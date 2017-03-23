# UNIKAT inventory system

## About this project

This project was created as a software project at the Ilmenau University of Technology. Its goal is to develop an inventory system for "UNIKAT", which is a FabLab organized by students. The inventory system has 2 main functions: The first one is to provide a database that contains all devices and materials belonging to UNIKAT as well as their current location and state. In addition to that, the inventory system is capable of creating and managing rentals of items, making it much easier to keep track of who rented something, when he rented it and also when he has to give it back.

### Developers

Kevin Bartsch </br>
Maximilian Rütz </br>
Franz Stecher </br>
Martin Werchan </br>
Oliver Sommer </br>
Connor Schellhorn </br>
David Scholz </br>
Andreas Wehenkel </br>

## Installation


### Setup Requirements (for vagrant box):
- VirtualBox
- Vagrant
- Cygwin, MinGV or Git

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
    cd /vagrant/code/laravel
    ```

4. Migrate the database:

    ```sh
    php artisan migrate
    ```

5. Seed the database:

    ```sh
    php artisan db:seed
    ```

6. Done - Open website:

    ```sh
    Type "localhost:8000" into your browser
    ```

7. Go to Admin Panel and login:

    ```sh
    Username: unikat@example.com
    Password: unikat
    (password should be changed after first login or in ~\code\laravel\database\seeds\MemberTableSeeder)
    ```

#### For Production
```$xslt
php artisan migrate
php artisan jwt:generat
php artisan db:seed --env=prod
php artisan admin:create
```