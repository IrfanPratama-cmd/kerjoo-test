## Install and run the application on a local development environment

```
composer install
```
OR
```
composer update
```
Create new .env and copy paste from .env.example

```
php artisan key:generate
```

Setting up Database 
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

```
php artisan serve
```

## Running PHP Unit Test
Documentation (https://laravel.com/docs/10.x/testing)

```
php artisan test
```

## ERD
https://drive.google.com/file/d/1ObkWXfRJcWqKtiGJENL39wOcQ6sc4Ud6/view?usp=sharing
