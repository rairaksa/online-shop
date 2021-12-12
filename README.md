# online-shop
Simple ecommerce API for order product flow

## Problem Analys
Misreported product stock may caused by validation failure of current running system. So, when flooded requests occur in some endpoint (add to cart, checkout and payment) the validation is malfunction then the customer can process the order without error.

## Solution
Implement backend stock validation

## Technology
Laravel

## Requirement
PHP 7.4

MySQL

Server (Apache / Nginx), or Web Server Bundle Apps (XAMPP / Laragon)

## How To Run?
Clone this project
Copy .env.example to .env
Update config .env (mysql credential)

Move to root directory of apps, and run command below :
```
composer install
composer dump-autoload
php artisan key:generate
php artisan migrate
php artisan serve
```