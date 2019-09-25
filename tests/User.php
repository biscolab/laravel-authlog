<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - User.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 24/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Tests;

use Biscolab\LaravelAuthLog\Traits\AuthLoggable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package Biscolab\LaravelAuthLog\Tests
 */
class User extends Authenticatable
{

    use Notifiable, AuthLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

}