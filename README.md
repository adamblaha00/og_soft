## Setup application
- create database
```bash
cp .env.example .env
```
- set database variables to .env
```bash
composer install
```
```bash
php artisan key:generate
```
```bash
php artisan migrate --seed
```
```bash
php artisan serve
```


### testing
```bash
php artisan test
```
