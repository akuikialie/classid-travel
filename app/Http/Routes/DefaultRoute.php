<?php

namespace App\Http\Routes;

use App\Http\Controllers\Mobile\AuthenticationSessionController;
use Dentro\Yalr\BaseRoute;
use Illuminate\Support\Facades\Cache;

class DefaultRoute extends BaseRoute
{

    protected string $prefix = '';

    protected string $name = '';

    /**
     * Register routes handled by this class.
     *
     * @return void
     */
    public function register(): void
    {
        // $this->router->get('{path?}', function () {
        //     if (request()->expectsJson()) {
        //         return response()->json('Welcome to Multi School');
        //     }

        //     return 'Welcome to Multi School';
        // })->where('path', '.*');

        $this->router->get('/', [AuthenticationSessionController::class, 'splash'])->middleware(['guest']);

        $this->router->get('/admin', function (){
            return redirect(route('admin.login'));
        });

        // $this->router->middleware(['auth', 'verified'])->group(function ($route) {

        //     $route->get($this->prefix('home'), function () {
        //         return view('pages.mobile.home.dashboard-index');
        //     })->name('home');

        //     $route->get($this->prefix('tabungan'), function () {
        //         return view('pages.mobile.tabungan.tabungan-index');
        //     })->name('tabungan');
        // });

        $this->router->get('cache-clear', function (string $key) {
            Cache::forget($key);
        })->middleware('auth');
    }
}
