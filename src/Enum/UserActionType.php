<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - UserActionType.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 13/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Enum;

class UserActionType
{

    /**
     * @var string
     */
    const LOGIN = 'login';

    /**
     * @var string
     */
    const LOGOUT = 'logout';

    /**
     * @var string
     */
    const FORCED_LOGOUT = 'forced_logout';
}