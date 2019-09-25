<?php
/**
 * Copyright (c) 2019 - present
 * Laravel Auth Log - UserEventSubscriber.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 21/9/2019
 * MIT license: https://github.com/biscolab/laravel-authlog/blob/master/LICENSE
 */

namespace Biscolab\LaravelAuthLog\Tests;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class UserEventSubscriber extends TestCase
{

    /**
     * @test
     */
    public function testLoginEvent()
    {

        Event::fake();

        $this->expectsEvents([
            Login::class
        ]);

        $user = User::create([
            'name'     => 'Roberto Belotti',
            'email'    => env('DEFAULT_USER_EMAIL'),
            'password' => bcrypt(env('DEFAULT_USER_PASSWORD'))
        ]);

//        $this->events->dispatch(new Events\Login(
//            $this->name, $user, $remember
//        ));

//        \event(new Login('web', $user, false));

        Auth::login($user);
        Event::assertDispatched(Login::class, function ($e) use ($user) {

            return $e->user->id === $user->id;
        });

    }

    /**
     * @test
     */
    public function testLogoutEvent()
    {

        Event::fake();

        $user = User::create([
            'name'     => 'Roberto Belotti',
            'email'    => env('DEFAULT_USER_EMAIL'),
            'password' => bcrypt(env('DEFAULT_USER_PASSWORD'))
        ]);

        event(new Logout('web', $user));

        Event::assertDispatched(Logout::class, function ($e) use ($user) {

            return $e->user->id === $user->id;
        });

    }
}