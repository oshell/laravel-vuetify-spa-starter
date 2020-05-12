# laravel-vuetify-spa-starter

Modern SPA starter template for rapid prototyping with the following features:
- JSON API
- JWT Authentication
- Login Form with validation
- Register Form with validation
- Email Verification Functionality
- Forgot Password Functionality
- Customizable Email Template
- Pre-configured SMTP for Gmail

## Stack

- PHP 7.2.5
- Laravel 7
- tymon/jwt-auth
- Vue 2
- Vuetify 2

## Setup

### 1.) Clone Repository
```
git clone https://github.com/oshell/laravel-vuetify-spa-starter.git
```

### 2.) Install PHP dependencies
```
cd laravel-vuetify-spa-starter
composer install
```
### 3.) Adjust local config
```
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```
- change `DB_DATABASE`,`DB_USERNAME`,`DB_PASSWORD`
- change `MAIL_USERNAME`,`MAIL_PASSWORD`

### 4.) Setup Database
Make sure the database from your `.env` file already exists.
```
php artisan migrate  
```

### 5.) Setup Frontend
```
yarn 
```

### 6.) Run API
```
php artisan serve
```

### 7.) Run Frontend
```
yarn hot
```
### 8.) Develop
Development server runs on `http://localhost:8000/`. It will serve `resources/views/spa.blade.php`, which includes compiled css and js. 

## Customize 
### Logo 
- Logo from the Forms and Appbar is located under `public/images/logo.png`
### Email
- Email Template under `resources/views/email/cta.blade.php`
- Email-Template for email verification and password reset is the same
- development under `http://localhost:8000/api/preview/mail`

## Usage
### API
- API routes are added under `routes/api.php`
- Routes which require authentication are moved into `Route::middleware('auth:api')->group()`

### Frontend
- token and user are cached in localstorage
- `auth` can be imported to access user information
- `import auth from '../auth'` and `auth.user()`
- `api` is used for all further requests and handles errors
- `LoadingOverlay` component can be used to suspend cards
