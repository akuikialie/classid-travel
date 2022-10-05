<?php

namespace App\Http\Routes\Mobile;

use App\Http\Controllers\TabunganController;
use Dentro\Yalr\BaseRoute;

class TabunganRoute extends BaseRoute
{

    protected string $prefix = 'tabungan';

    protected string $name = 'tabungan';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function ($route) {

            $route->get($this->prefix(''), [TabunganController::class, 'index'])->name('tabungan.index');

            $route->get($this->prefix('show/{tabungan}'), [TabunganController::class, 'show'])->name('tabungan.show');

        });
    }
}
