<?php

namespace App\Http\Routes\Mobile;

use App\Http\Controllers\Mobile\HomeController;
use Dentro\Yalr\BaseRoute;

class HomeRoute extends BaseRoute
{

    protected string $prefix = 'home';

    protected string $name = 'home';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function ($route) {

            $route->get($this->prefix(''), [HomeController::class, 'index'])->name('home.index');
        });
    }
}
