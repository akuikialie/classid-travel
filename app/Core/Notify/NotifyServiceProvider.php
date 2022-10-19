<?php

namespace App\Core\Notify;

use Illuminate\Support\ServiceProvider;

class NotifyServiceProvider extends ServiceProvider
{

    public function register()
    {
        // Binding required classes to app
        $this->app->bind(
            \App\Core\Notify\Sessions\SessionStore::class,
            \App\Core\Notify\Sessions\AlertSessionStore::class,
            \App\Core\Notify\ToNotify::class,
        );

        // Register the main class to use with the facade
        $this->app->singleton('notify', function ($app) {
            return $this->app->make(NotifyConfig::class);
        });
    }

    public function boot()
    {
        /*
         * Registering the helper methods to package
         */
        $this->registerHelpers();

        /*
        * Registering the Package Views
        */
        $this->registerViews();
    }

    /**
     * Register helpers file
     *
     * @return void
     */
    public function registerHelpers()
    {
        // Load the helpers in functions.php
        if (file_exists($file = app_path() . '/Core/Notify/Function.php')) {
            require $file;
        }
    }

    /**
     * Register the package's views.
     *
     * @return void
     */
    private function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'notify');
    }
}
