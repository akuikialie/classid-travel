<?php

namespace App\Http\Routes\Mobile;

use Dentro\Yalr\BaseRoute;

class HomeRoute extends BaseRoute
{

    protected string $prefix = 'Beranda';

    protected string $name = 'Beranda';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function ($route) {

            $route->get($this->prefix('home'), function () {
                return view('pages.mobile.home.dashboard-index');
            })->name('home.index');

        });
    }
}
