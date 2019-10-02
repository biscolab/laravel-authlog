<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - AuthSession.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 23/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class AuthSession
 * @method static string getCurrentSessionId()
 * @method static string getSessionAuthLogIdKey()
 * @method static string setAuthLogId()
 * @method static string getAuthLogId()
 * @method static string getTableUserIdType()
 * @method static string getMigrateUserIdType()
 * @method static bool userTableIdIsInt()
 * @package Biscolab\LaravelAuthLog\Facades
 */
class AuthLog extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {

        return 'authlog';
    }

}