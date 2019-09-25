<?php
/**
 * Created by PhpStorm.
 * User: biscottino
 * Date: 13/09/19
 * Time: 15:16
 */

namespace Biscolab\LaravelAuthLog;

use Biscolab\LaravelAuthLog\Commands\GetLoggedUsersCommand;
use Biscolab\LaravelAuthLog\Listeners\UserEventSubscriber;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AuthLogServiceProvider extends ServiceProvider
{

    protected $subscribe = [
        UserEventSubscriber::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../config/authlog.php' => config_path('authlog.php'),
        ], 'config');

        $this->registerEventSubscribers();

        $this->registerAuthSessionFacades();

        if ($this->app->runningInConsole()) {
            $this->commands([
                GetLoggedUsersCommand::class,
            ]);
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config/authlog.php', 'authlog'
        );

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {

        return ['authlog'];
    }

    /**
     * Subscribe the authentication events.
     *
     * @return void
     */
    protected function registerEventSubscribers()
    {

        if (config('authlog.enabled')) {

            foreach ($this->subscribe as $subscriber) {
                Event::subscribe($subscriber);
            }
        }

    }

    protected function registerAuthSessionFacades()
    {

        $this->app->bind('authlog', function () {

            return new LaravelAuthLog();

        });
    }

}