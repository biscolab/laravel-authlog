<?php
/**
 * Created by PhpStorm.
 * User: biscottino
 * Date: 13/09/19
 * Time: 15:50
 */

namespace Biscolab\LaravelAuthLog\Listeners;

class UserEventSubscriber
{

    /**
     * @param $event
     */
    public function handleUserLogin($event)
    {
        $event->user->registerLogin();
    }

    /**
     * @param $event
     */
    public function handleUserLogout($event)
    {
        $event->user->registerLogout();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {

        $events->listen(
            'Illuminate\Auth\Events\Login',
            self::class . '@handleUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            self::class . '@handleUserLogout'
        );
    }
}