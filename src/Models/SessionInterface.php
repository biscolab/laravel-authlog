<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - SessionInterface.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 18/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Session
 * @property string id
 * @property int    user_id
 * @property string payload
 * @property string decoded_payload
 * @property string ip_address
 * @property string user_agent
 * @property int    last_activity
 * @package Biscolab\LaravelAuthLog\Models
 */
interface SessionInterface
{

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo;

    /**
     * decoded_payload property
     * @return string
     */
    public function getDecodedPayloadAttribute(): string;
}