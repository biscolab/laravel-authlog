<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - helpers.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 17/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

use Biscolab\LaravelAuthLog\Facades\AuthLog;
use Biscolab\LaravelAuthLog\LaravelAuthLog;

if (!function_exists('authlog')) {

    /**
     * @return LaravelAuthLog
     */
    function authlog(): LaravelAuthLog
    {

        return app('authlog');
    }
}

if (!function_exists('getCurrentSessionId')) {

    /**
     * @return string
     */
    function getCurrentSessionId(): string
    {

        return AuthLog::getCurrentSessionId();
    }
}

if (!function_exists('getSessionAuthLogIdKey')) {

    /**
     * @return string
     */
    function getSessionAuthLogIdKey(): string
    {

        return AuthLog::getSessionAuthLogIdKey();
    }
}

if (!function_exists('setAuthLogId')) {

    /**
     * @param int $auth_log_id
     */
    function setAuthLogId(int $auth_log_id): void
    {

        AuthLog::setAuthLogId($auth_log_id);
    }
}

if (!function_exists('getAuthLogId')) {

    /**
     * @return int|null
     */
    function getAuthLogId(): ?int
    {

        return AuthLog::getAuthLogId();
    }
}
