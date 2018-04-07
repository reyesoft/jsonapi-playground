#!/bin/sh

## Copyright (C) 1997-2017 Reyesoft <info@reyesoft.com>.
## This file is part of a Reyesoft Project. This can not be copied and/or
## distributed without the express permission of Reyesoft

echo '#### MYSQL CONFIGURATION ####'
service mysql start
mysql --password=root -e "DROP DATABASE IF EXISTS idpal; CREATE DATABASE IF NOT EXISTS idpal;"
mysql --password=root -e 'create database mysql_test;' -v
mysql --password=root -e "CREATE USER 'forge'@'localhost' IDENTIFIED BY 'secret';"
mysql --password=root -e 'GRANT ALL PRIVILEGES ON * . * TO 'forge'@'localhost';'

echo '#### ENV LARAVEL CONFIGURATION ####'
cat .env.example >> .env.testing
sed -i -e "
s/APP_ENV.*$/APP_ENV=testing/g;
s/DB_HOST.*$/DB_HOST=localhost/g;
s/DB_DATABASE.*$/DB_DATABASE=mysql_test/g;
s/DB_USERNAME.*$/DB_USERNAME=forge/g;
s/DB_PASSWORD.*$/DB_PASSWORD=secret/g;
s/QUEUE_DRIVER.*$/QUEUE_DRIVER=sync/g;
" .env.testing

echo '#### COMPOSER THINGS ####'
composer install --no-interaction --no-progress &&

echo '#### BEAUTIFUL CODE <3 ####' &&
sh resources/bash/bc.sh || { ERROR=$?; echo 'Beautiful code failed (bc.sh)' ; exit $ERROR; }

echo '#### ARTISAN THINGS ####' &&
# Migrate and seed database using the APP_ENV environment variable of 'testing'
php artisan migrate --force --seed --env=testing &&

### PHPUNIT
## don't add tests here, please use phpunit.xml
echo "### PHPUNIT" &&
./vendor/bin/phpunit &&

# test if all migrations rollback fine
echo "### artisan migrate:reset" &&
php artisan migrate:reset --env=testing &&

exit $?
