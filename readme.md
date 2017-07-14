# Instrucciones

### 1er paso
```
php artisan migrate
```

### 2do paso
```
php artisan db:seed
```

### 3er paso
```
php artisan serve
```

### Como instalar php 7.1 en ubuntu 

```
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get remove php7.0
sudo apt-get install php7.1
sudo apt-get install   php7.1-mcrypt php7.1-gd php7.1-soap php7.1-mbstring php7.1-gd php7.1-xml php7.1-json php7.1-zip  php7.1-bz2 php7.1-mysql php7.1-curl
```

### Resources
##### GETALL
```
[GET] localhost/{resource}
```
##### GET
```
[GET] localhost/{resource}/{resource_id}
```
##### UPDATE
```
[PUT/PATCH] localhost/{resource}/{resource_id}
```
##### DELETE
```
[DELETE] localhost/{resource}/{resource_id}
```

### Example
```
localhost:8000/authors
```
##### GET
```
localhost:8000/authors/1
```
##### UPDATE
```
localhost:8000/authors/1
```
##### DELETE
```
localhost:8000/authors/1
```