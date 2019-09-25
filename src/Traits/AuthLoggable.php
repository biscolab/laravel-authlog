<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - AuthLoggable.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 14/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Traits;

use Biscolab\LaravelAuthLog\Models\AuthLog;
use Biscolab\LaravelAuthLog\Models\AuthLogInterface;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Session;

/**
 * Trait AuthLoggable
 * @package Biscolab\LaravelAuthLog\Traits
 */
trait AuthLoggable
{

    /**
     * @return array
     */
    public function getSessionData(): array
    {

        $response = [
            'user_id'    => $this->id,
            'ip'         => request()->ip(),
            'session_id' => getCurrentSessionId(),
            'user_agent' => request()->userAgent(),
        ];

        return array_merge($response, []);
    }

    /**
     * @return AuthLogInterface|null
     */
    public function registerLogin(): ?AuthLogInterface
    {

        if (!authlog()->canRegisterAuthLog()) {
            return null;
        }

        $data = $this->getSessionData();
        $auth_log_model_class = config('authlog.authlog_model');

        /** @var AuthLog $auth_log */
        $auth_log = (new $auth_log_model_class)->createLogin($data);

        Session::put(getSessionAuthLogIdKey(), $auth_log->id);

        return $auth_log;
    }

    /**
     * @param int|null $blame_on_user_id
     *
     * @return AuthLogInterface|null
     */
    public function registerLogout(?int $blame_on_user_id = null): ?AuthLogInterface
    {

        if (!authlog()->canRegisterAuthLog()) {
            return null;
        }
        if (!empty($this->current_auth_log)) {
            return $this->current_auth_log->createLogout($blame_on_user_id);
        }

        return null;
    }

    /**
     * logs (general)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {

        $auth_log_model_class = config('authlog.authlog_model');

        return $this->hasMany($auth_log_model_class, 'user_id')->orderBy('id', 'desc');
    }

    /**
     * logins (from logs)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logins()
    {

        return $this->logs()->whereNull('logged_out_at');
    }

    /**
     * logouts (from logs)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logouts()
    {

        return $this->logs()->whereNotNull('logged_out_at')->whereNull('blame_on_user_id')->where('killed_from_console',
            false);
    }

    /**
     * forced_logouts (from logs)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forced_logouts()
    {

        return $this->logs()->whereNotNull('logged_out_at')->where(function ($query) {

            $query->whereNotNull('blame_on_user_id')
                ->orWhere('killed_from_console', true);
        });
    }

    /**
     * session_is_active
     *
     * @return mixed
     */
    public function getSessionIsActiveAttribute()
    {

        /** @var AuthLog $this */
        $login = $this->logins()->where('session_id', getCurrentSessionId())->orderBy('id', 'desc')->first();

        return (is_null($login)) ? true : $login->is_active_session;
    }

    /**
     * @return HasOne
     */
    public function current_auth_log(): HasOne
    {

        $auth_log_model_class = config('authlog.authlog_model');

        return $this->hasOne($auth_log_model_class, 'user_id')->where('id', getAuthLogId());
    }

}