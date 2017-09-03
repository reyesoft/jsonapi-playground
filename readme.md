# JsonApi Playground

## Installation

### Laradock

Agregar a `laradock/nginx/sites/laravel-jsonapi.conf`

```
server_name laravel-jsonapi.dev;
root /var/www/laravel-jsonapi/public;
```

y a `/etc/hosts`

```
127.0.0.1   laravel-jsonapi.dev
```

Now, just need

```bash
sudo docker-compose up nginx mysql -d
```

and go to <http://laravel-jsonapi.dev/>

### Apache + MySQL

```
composer install
php artisan migrate --seed
php artisan serve
```

## Resources

## Resources

- authors
- books
- photos

## Entry points

```
[GET] localhost/v2/{resource}

[GET] localhost/v2/{resource}/{resource_id}

[GET] localhost/v2/{resource}/{resource_id}/related

[PUT/PATCH] localhost/v1/{resource}/{resource_id}

[DELETE] localhost/v1/{resource}/{resource_id}
```

## PhpCsFixer autofix

```
./vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --path-mode=intersection ./app ./bootstrap/ ./config/ ./database/ ./resources/ ./tests/
```
