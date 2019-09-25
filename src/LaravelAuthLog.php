<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - AuthSessionHandler.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 23/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog;

use Illuminate\Support\Facades\Session;

/**
 * Class AuthSessionHandler
 * @package Biscolab\LaravelAuthLog
 */
class LaravelAuthLog
{

    /**
     * @return bool
     */
    public function skipByIp(): bool
    {

        $ip_whitelist = config('authlog.skip_ip', '');

        $ip_whitelist = array_map(function ($item) {

            return trim($item);
        }, explode(',', $ip_whitelist));

        return in_array($this->getCurrentIp(), $ip_whitelist);
    }

    /**
     * @return bool
     */
    public function canRegisterAuthLog(): bool
    {
        return !$this->skipByIp() && $this->isEnabled();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return config('authlog.enabled', false);
    }

    /**
     * @return string
     */
    public function getCurrentIp(): string
    {

        return request()->ip();
    }

    /**
     * @return string
     */
    public function getCurrentSessionId(): string
    {

        return session()->getId();
    }

    /**
     * @return string
     */
    public function getSessionAuthLogIdKey(): string
    {

        return config('authlog.session_auth_log_id_key', 'auth_log_id');
    }

    /**
     * @param int $auth_log_id
     */
    public function setAuthLogId(int $auth_log_id): void
    {

        Session::put($this->getSessionAuthLogIdKey(), $auth_log_id);
    }

    /**
     * @return int|null
     */
    public function getAuthLogId(): ?int
    {

        return Session::get($this->getSessionAuthLogIdKey());
    }
}