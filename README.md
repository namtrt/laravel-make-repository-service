# Laravel 5+ Php Artisan Make:Repository, Php Artisan Make:Service
A package for addding `php artisan make:repository`, `php artisan make:service` command to Laravel 5+

## Installation
Require the package with composer using the following command:

`composer require thanhnamcnv/laravel-make-repository-service --dev`

Or add the following to your composer.json's require-dev section and `composer update`

```json
"require-dev": {
          "thanhnamcnv/laravel-make-repository-service": "^1.0.0"
}
```
## Usage
`php artisan make:repository your-repository-name`

`php artisan make:service your-service-name`

Example:
```
php artisan make:repository UserRepository

php artisan make:service UserService
```
or
```
php artisan make:repository Backend\UserRepository

php artisan make:service Backend\UserService
```