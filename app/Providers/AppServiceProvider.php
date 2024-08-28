<?php

namespace App\Providers;

use App\Models\Tenant\Tenant;
use Exception;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('request_id', fn ($app) => uniqid("REQUEST_"));

        $this->app->singleton('notifLog', fn () => app('log')->channel('msnotif'));

        app()->singleton('activeTenant', function(): Tenant|null {
            try {
                $tenant = Tenant::query()
                    ->with('tenantData')
                    ->where('is_active', true);

                if (auth()->user()?->tenant_id ?? null){
                    $tenant->where('id', auth()->user()->tenant_id);
                } else {
                    $domain = request()->getHost();
                    $tenant->where('app_domain', $domain);
                }

                return $tenant->first();
            } catch (Exception $e) {
                //
            }
            return null;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->secure() || in_array(app()->environment(), ['demo', 'staging', 'prod', 'production'])) {
            $this->app['request']->server->set('HTTPS', true);
            URL::forceScheme('https');
        }

        Vite::useScriptTagAttributes([
            'type' => 'text/javascript'
        ]);
    }
}
