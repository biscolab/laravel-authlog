<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - AuthLogMiddleware.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 16/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Middleware;

use Biscolab\LaravelAuthLog\Models\AuthLog;
use Closure;
use Illuminate\Http\Request;

/**
 * Class AuthLogMiddleware
 * @package Biscolab\LaravelAuthLog\Middleware
 */
class AuthLogMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $response = $next($request);
        $response_array = json_decode($response->getContent(), true);

        $auth_log_id = getAuthLogId();

        if ($auth_log_id) {

            /** @var AuthLog $auth_log */
            $auth_log = AuthLog::where('session_id', '<>', getCurrentSessionId())->find($auth_log_id);
            if (!empty($auth_log->id)) {
                $auth_log->changeSessionId();
            }
        }

        if (!empty($response_array)) {

            if ($request->ajax() && config('authlog.add_auth_log_id_to_ajax_response')) {
                $response_array[config('authlog.ajax_response_auth_log_id_name')] = $auth_log_id;
                $response = $response->setContent(json_encode($response_array));
            }

        }
        if (config('authlog.add_auth_log_id_header_to_http_response')) {
            $response->header(config('authlog.auth_log_id_header_name'), $auth_log_id);

        }

        return $response;
    }
}
