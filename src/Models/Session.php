<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - Session.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 14/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

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
class Session extends Model implements SessionInterface
{

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'sessions';

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * decoded_payload property
     * @return string
     */
    public function getDecodedPayloadAttribute(): string
    {

        return base64_decode($this->payload);
    }
}