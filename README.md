# Laravel Auth-log
User authentication log for Laravel 5, 6 and 7.
This package allows you to log user's authentication and force its logout if necessary!

**This package is still unstable. Please report any bug`and documentation lack in order to fix that promptly**.

Thanks for your help.

## System requirements

Set `session.driver` value
> To use this package the only allowed values of `session.driver` are `file`, `database`, `redis` (at the moment).

## Install

You can install the package via composer:
```sh
$ composer require biscolab/laravel-authlog
```
Laravel 5.5 (or greater) uses package auto-discovery, so doesn't require you to manually add the Service Provider, but if you don't use auto-discovery `AuthLogServiceProvider` must be registered in `config/app.php`:
```php
'providers' => [
    ...
    Biscolab\LaravelAuthLog\AuthLogServiceProvider::class,
];
```
You can use the facade for shorter code. Add `AuthLog` to your aliases:
```php
'aliases' => [
    ...
    'AuthLog' => Biscolab\LaravelAuthLog\Facades\AuthLog::class,
];
```

## Publish package
Publish configuration and migration files using the following artisan command:
```sh
$ php artisan vendor:publish --provider="Biscolab\LaravelAuthLog\AuthLogServiceProvider"
```

## Configuration

Edit `config/authlog.php`

| Variable name | Type | Description | Default value |
|----------------|-----------------|---------------------|-----------------|
| `safe_mode` | `bool` | Avoid to force logout by yourself | `true` |
| `enabled` | `bool` | If `true` the package is active and user's authentication will be logged | `true` |
| `skip_ip` | `string` | A whitelist of IP addresses (CSV format) that, if recognized, disable the package  | `''` |
| `table_name` | `string` | The name of the AuthLog database table | `authlog` |
| `users_table_size` | `string` | Users table size in order to add foreign keys (`int` means INT, `big` means BIGINT). Since Laravel 5.8, value should be `big` (*) | `big` |
| `authlog_model` | `string` | AuthLog class. You can change ìt **BUT** your custom class **MUST** implements `'Biscolab\LaravelAuthLog\Models\AuthLogInterface'` | `'Biscolab\LaravelAuthLog\Models\AuthLog'` |
| `session_model` | `string` | Session class. You can change ìt **BUT** your custom class **MUST** implements `'Biscolab\LaravelAuthLog\Models\SessionInterface'` | `'Biscolab\LaravelAuthLog\Models\Session'` |
| `session_auth_log_id_key` | `string` | Session key used to store your AuthLog ID | `'auth_log_id'` |
| `add_auth_log_id_to_ajax_response` | `bool` | If `true` AuthLog ID will be added to your AJAX responses | `true` |
| `ajax_response_auth_log_id_name` | `string` | AJAX response key used to send your AuthLog ID | `'auth_log_id'` |
| `add_auth_log_id_header_to_http_response` | `bool` | If `true` AuthLog ID will be added to your response headers | `true` |
| `auth_log_id_header_nameauth_log_id_header_name` | `string` | AuthLog ID header name | `'X-Auth-Log-Id'` |

> Remember to run the `php artisan config:cache` command

> (*) If you are using a custom `users` table please edit `datatbase/migratons/2019_09_19_000000_create_authlog_table.php` after run `vendor:publish` artisan command and before run `migration` artisan command

## Add `AuthLoggable` trait to `User` model

Use `Biscolab\LaravelAuthLog\Traits\AuthLoggable` in `App\User`

```php
<?php

namespace App;

// .....

use Biscolab\LaravelAuthLog\Traits\AuthLoggable;

class User extends Authenticatable
{
    use AuthLoggable;
    
    // other traits
    // ......

```

## Database

> Your users' table id MUST be either of type `unsignedInteger` or `unsignedBigInteger`. If you have a custom users' table edit `datatbase/migratons/2019_09_19_000000_create_authlog_table.php` after `vendor:publish` artisan command

Run migrations

```sh
php artisan migrate
```

AuthLog database table will be created.


## Middleware

### Register `AuthLogMiddleware
Register `AuthLogMiddleware` in `app/Http/Kernel.php`. This middleware will handle user authentication session ID. 

```php

protected $routeMiddleware = [
    ...
    'auth.log' =>  \Biscolab\LaravelAuthLog\Middleware\AuthLogMiddleware::class
];

````

### Add `AuthLogMiddleware` to routes

```php
Route::group(['middleware' => ['auth.log']], function() {

    // Your routes
});
```

## Handle logged users
### Artisan Command

To handle auth sessions type the following artisan command

```sh
php artisan authlog:logged
```

The list of logged users will be shown

```sh
+--------+------------------------------------------+-----------------------+---------------------+
| Log ID | Session ID                               | User                  | Logged @            |
+--------+------------------------------------------+-----------------------+---------------------+
| 604    | teq4LmVM4u4sdhFTKnGsKeWs3IBOLAIOXB1c4ioy | Roberto Belotti (#22) | 2019-09-25 22:56:33 |
+--------+------------------------------------------+-----------------------+---------------------+

Type Log ID to kill session. Type "exit" to quit:
```

Now you can either quit typing `exit` or force user logout typing the specific Log ID, in this case `604`.

```
 > 604
 
Session "teq4LmVM4u4sdhFTKnGsKeWs3IBOLAIOXB1c4ioy" deleted. "Roberto Belotti" user logged out
No logged user, please type "exit" to quit
```
