#!/bin/sh
/opt/plesk/php/7.1/bin/php artisan migrate:fresh &&
/opt/plesk/php/7.1/bin/php artisan db:seed