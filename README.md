# E-COURSE WEB

## Database Configuration
* DB_DATABASE

## PHP Extensions
* https://laravel.com/docs/8.x

## Spatie Role
Tambahkan connection pada vendor\spatie\laravel-permission\src\Models\Role
```
    public $incrementing = false;

    public $keyType = 'string';

```
## Spatie Permission
Tambahkan connection pada vendor\spatie\laravel-permission\src\Models\Permission
```
    public $incrementing = false;

    public $keyType = 'string';
```

## Migration Command
```
php artisan migrate:fresh
php artisan migrate
```

## Clearing Cache Command
```
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```
## Class Name Priority Style
* Library
* Model
* Controller
* Middleware
* Request
* Observer
* Provider
* Factory
* Test

## Product
* Materi course video
* Materi course file

## Debug by yourself first, Don't expect I will debug for you
