<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - authlog.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 13/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

return [

    /**
     * If "true" it avoids to "kill" your own session
     */
    'safe_mode' => env('AUTHLOG_SAFE_MODE', true),

    /**
     * If "true" Auth Log package is enabled
     */
    'enabled' => env('AUTHLOG_ENABLED', true),

    /**
     * Coming connections from following IP(s) won't be recorded (CSV format)
     */
    'skip_ip' => env('AUTHLOG_SKIP_IP', null),

    /**
     * AuthLog model table name
     */
    'table_name' => 'authlog',

    /**
     * Users table size in order to add foreign keys
     * Supported: 'int', 'big'
     */
    'users_table_size' => 'big',

    /**
     * AuthLog model class MUST implements Biscolab\LaravelAuthLog\Models\AuthLogInterface
     */
    'authlog_model' => 'Biscolab\LaravelAuthLog\Models\AuthLog',

    /**
     * Session model class MUST implements Biscolab\LaravelAuthLog\Models\SessionInterface
     */
    'session_model' => 'Biscolab\LaravelAuthLog\Models\Session',

    /**
     * Session key used to store your AuthLog ID
     */
    'session_auth_log_id_key' => 'auth_log_id',

    /**
     * If "true" AuthLog ID will be added to your AJAX responses
     */
    'add_auth_log_id_to_ajax_response' => true,

    /**
     * AJAX response key used to send your AuthLog ID
     */
    'ajax_response_auth_log_id_name' => 'auth_log_id',

    /**
     * If "true" AuthLog ID will be added to your response headers
     */
    'add_auth_log_id_header_to_http_response' => true,

    /**
     * AuthLog ID header name
     */
    'auth_log_id_header_name' => 'X-Auth-Log-Id',

];
