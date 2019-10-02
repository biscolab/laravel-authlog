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

use Biscolab\LaravelAuthLog\Listeners\UserEventSubscriber;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class UserEventSubscriberTest extends TestCase
{

    private $user;

    /**
     * @test
     */
    public function testLoginEvent()
    {

        Event::fake();
        $user = $this->user;
        Auth::login($user);


        Event::assertDispatched(Login::class, function ($e) use ($user) {
            $subscriber = new UserEventSubscriber();


            $subscriber->handleUserLogin($e);
            return $e->user->id === $user->id;
        });

        $this->user->load(['logs']);

        $this->assertEquals(1, $this->user->logs->count());

        event(new Logout('web', $user));
        Event::assertDispatched(Logout::class, function ($e) use ($user) {
            $subscriber = new UserEventSubscriber();


            $subscriber->handleUserLogout($e);
            return $e->user->id === $user->id;
        });

        $this->user->load(['logouts']);

        $this->assertEquals(1, $this->user->logouts->count());

    }

    protected function setUp(): void
    {

        parent::setUp();
        $this->carbon = \Mockery::mock(Carbon::class);
        $this->request = \Mockery::mock(Request::class);
        $this->user = User::create([
            'name'     => 'Roberto Belotti',
            'email'    => env('DEFAULT_USER_EMAIL'),
            'password' => bcrypt(env('DEFAULT_USER_PASSWORD'))
        ]);
    }
}