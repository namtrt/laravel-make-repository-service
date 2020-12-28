# Laravel 6+ Php Artisan Make:Repository, Php Artisan Make:Service
A package for addding `php artisan make:repository`, `php artisan make:service` command to Laravel 6+

## Installation
Require the package with composer using the following command:

`composer require thanhnamcnv/laravel-make-repository-service --dev`

Or add the following to your composer.json's require-dev section and `composer update`

```json
"require-dev": {
          "thanhnamcnv/laravel-make-repository-service": "^2.*"
}
```

In your config/app.php add NamTran\LaravelMakeRepositoryService\RepositoryServiceProvider::class to the end of the providers array:
```php
'providers' => [
    ...
    NamTran\LaravelMakeRepositoryService\RepositoryServiceProvider::class,
],
```

Publish Configuration
```bash
php artisan vendor:publish --provider "NamTran\LaravelMakeRepositoryService\RepositoryServiceProvider"
```

## Usage
`php artisan make:repository your-repository-name`

`php artisan make:service your-service-name`

Example:
```
php artisan make:repository User

php artisan make:service User
```
or
```
php artisan make:repository Backend\User

php artisan make:service Backend\User
```