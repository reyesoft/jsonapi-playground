# Laravel JSONAPI

## Installation

### Environment

#### Laradock

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

#### Apache + MySQL

### Laravel

```
composer install
php artisan migrate --seed
php artisan serve
```

## Resources

### GETALL

```
[GET] localhost/{resource}
```

### GET

```
[GET] localhost/{resource}/{resource_id}
```

### UPDATE

```
[PUT/PATCH] localhost/{resource}/{resource_id}
```

### DELETE

```
[DELETE] localhost/{resource}/{resource_id}
```

### Example

```
localhost:8000/authors
```

### GET

```
localhost:8000/authors/1
```

### UPDATE

```
localhost:8000/authors/1
```

### DELETE

```
localhost:8000/authors/1
```

## Como instalar php 7.1 en ubuntu

```
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get remove php7.0
sudo apt-get install php7.1
sudo apt-get install   php7.1-mcrypt php7.1-gd php7.1-soap php7.1-mbstring php7.1-gd php7.1-xml php7.1-json php7.1-zip  php7.1-bz2 php7.1-mysql php7.1-curl
```
