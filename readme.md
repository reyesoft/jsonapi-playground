# JsonApi Playground

Online version: <http://jsonapiplayground.reyesoft.com/> 😁

## Installation

### With Laradock

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

### With Artisan

```
composer install
php artisan migrate --seed
php artisan serve
```

## Resources

- authors
- books
- series
- chapters
- stores
- photos

### Entry points

```
[GET] localhost/v2/{resource}

[GET] localhost/v2/{resource}/{resource_id}

[GET] localhost/v2/{resource}/{resource_id}/related

[PUT/PATCH] localhost/v1/{resource}/{resource_id}

[DELETE] localhost/v1/{resource}/{resource_id}
```

## Development

### Tests

```
phpunit
```

## PhpCsFixer autofix

```
sh autofix.sh
```
