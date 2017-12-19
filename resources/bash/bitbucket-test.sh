#!/bin/sh

#### LARAVEL
echo '#### MYSQL CONFIGURATION ####'
service mysql start
mysql -uroot -e "DROP DATABASE IF EXISTS idpal; CREATE DATABASE IF NOT EXISTS idpal;"
mysql -e 'create database japlayground_test;' -v
mysql -e "CREATE USER 'forge'@'localhost' IDENTIFIED BY 'secret';"
mysql -e 'GRANT ALL PRIVILEGES ON * . * TO 'forge'@'localhost';'
####
echo '#### ENV LARAVEL CONFIGURATION ####'
touch .env
echo "APP_NAME=JsonApiPlayground">> .env
echo "APP_ENV=develop">> .env
echo "APP_DEBUG=true" >> .env
echo "APP_KEY=iXGNAjVbw7731rzhGp1OsTxxGeSyK4zd" >> .env
echo "APP_URL=YOUR_PREFER_URL" >> .env
echo "DB_HOST=localhost" >> .env
echo "DB_CONNECTION=mysql" >> .env
echo "DB_DATABASE=japlayground_test" >> .env
echo "DB_USERNAME=forge">> .env
echo "DB_PASSWORD=secret" >> .env
echo "CACHE_DRIVER=file" >> .env
echo "SESSION_DRIVER=file" >> .env
echo "QUEUE_DRIVER=sync" >> .env

composer install --no-interaction

echo '#### BEAUTIFUL CODE <3 ####'
sh resources/bash/bc.sh || { ERROR=$?; echo 'Beautiful code failed (bc.sh)' ; exit $ERROR; }

echo '#### ARTISAN THINGS ####'
# Migrate and seed database using the APP_ENV environment variable of 'testing'
php artisan migrate --force --seed &&

### PHP UNIT
./vendor/bin/phpunit &&

# test if all migrations rollback fine
php artisan migrate:reset &&

# test if all migrations rollback fine
php artisan migrate:reset &&

exit $?
