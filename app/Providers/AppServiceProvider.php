<?php

namespace App\Providers;

use App\Actions\CreateNewUser;
use App\Models\Tenant\Tenant;
use Exception;
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
        $this->app->singleton('request_id', fn ($app) => uniqid("REQUEST_"));

        $this->app->singleton('notifLog', fn() => app('log')->channel('msnotif'));

        app()->singleton('activeTenant', function(): ?Tenant {
            try {
                if (auth()->user()?->tenant_id){
                    return Tenant::find(auth()->user()?->tenant_id);
                }

                $domain = request()->getHost();
                return Tenant::query()->where([
                    'app_domain' => $domain,
                    'is_active' => true,
                ])->first();
            } catch (Exception $e) {
                return null;
            }
        });
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
