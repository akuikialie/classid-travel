<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Models\Tenant\Tenant;
use Exception;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Fluent;
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

        $this->app->singleton('tenantOption', function (): Fluent {
            $host = request()->getHttpHost();
            return app('cache')
                ->remember('tenant-opt-'.$host, now('Asia/Jakarta')->endOfDay(), function () use ($host): Fluent {
                    $tenant = Tenant::query()->select('options')->where('app_domain', $host)->first();
                    return new Fluent($tenant?->options ?? []);
                });
        });

        app()->singleton('activeTenant', function(): Tenant|null {
            try {
                $tenant = Tenant::query()
                    ->with('tenantData')
                    ->where('is_active', true);

                if (auth()->user()?->tenant_id ?? null){
                    $tenant->where('id', auth()->user()->tenant_id);
                } else {
                    $domain = request()->getHttpHost();
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

//        if (stripos(request()->path(), 'api') === false) {
//            $this->app->singleton(
//                \Illuminate\Contracts\Debug\ExceptionHandler::class,
//                Handler::class
//            );
//        }

        Vite::useScriptTagAttributes([
            'type' => 'text/javascript'
        ]);
    }
}
