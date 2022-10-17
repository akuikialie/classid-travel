<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (request()->secure() || in_array(app()->environment(), ['demo', 'staging', 'prod', 'production'])) {
            $this->app['request']->server->set('HTTPS', true);
            \URL::forceScheme('https');
        }
        
        Vite::useScriptTagAttributes([
            'type' => 'text/javascript'
        ]);
    }
}
