# Article Backend (Laravel)

This is the backend part of the @rticle.io project built using Laravel framework.

## REQUIREMENT

1. Composer
2. PHP 7.2
3. MYSQL 5.7
4. LARAVEL 5.8
5. LARAVEL SOCIALITE 3.2
6. STRIPE 7.37
7. TWILIO 6.7
8. MAILTRAP

## Getting Started

Step 1: [Download and Install composer](https://www.digitalocean.com/community/tutorials/how-to-install-composer-on-ubuntu-20-04-quickstart)

Step 2: [Download and Install PHP and MYSQL](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04)

Step 3: [Download and Install LARAVEL](https://laravel.com/docs/7.x/installation)

Step 4: Move to your project folder

Step 5: Type `composer install` and `composer dump-autoload` to install and refresh all the dependencies

Step 4: Create an .env file `touch .env`.

Step 5: Copy the content of .env.example file to .env file.

STep 6: Type `php artisan key:generate && php artisan cache:clear && php artisan config:clear && composer dump-autoload`.

Step 7: Type `php artisan migrate` to add migration in MYSQL

Step 8: Type `chmod 777 -R storage bootstrap/cache public/uploads` to give permissions for image upload and logging exception

Step 9: Lastly, type `php artisan serve` to start local development server`
