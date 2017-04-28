# UNIKAT inventory system

## About this project

This project was created as a software project at the Ilmenau University of Technology. Its goal is to develop an inventory system for "UNIKAT", which is a FabLab organized by students. The inventory system has 2 main functions: The first one is to provide a database that contains all devices and materials belonging to UNIKAT as well as their current location and state. In addition to that, the inventory system is capable of creating and managing rentals of items, making it much easier to keep track of who rented something, when he rented it and also when he has to give it back.

## Installation

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

4. Copy settings and set `app` and `jwt` key 

    ```sh
    cp .env.example .env
    nano .env
    ```

4. Migrate the database:

    ```sh
    php artisan migrate
    ```

5. Create a admin:

    ```sh
    php artisan admin:create
    ```

6. Done - Open website:

    ```sh
    Type "localhost:8000" into your browser
    ```

#### For Production
Set APP_DEBUG to `false` and APP_ENV to `production`.
If you have deployed this to a subfolder, add the `RewriteBase` to the `code/laracel/public/.htaccess`:
```
    RewriteEngine On
    RewriteBase /your/sub/path/
    # Redirect Trailing Slashes If Not A Folder...
```

## Customization

  1. Change constants in code/laravel/public/js/app.js
  2. Replace code/laravel/public/img/logo.png