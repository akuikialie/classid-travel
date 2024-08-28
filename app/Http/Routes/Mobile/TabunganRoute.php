<?php

namespace App\Http\Routes\Mobile;

use App\Enums\RoleEnum;
use App\Http\Controllers\Mobile\TabunganController;
use Dentro\Yalr\BaseRoute;

class TabunganRoute extends BaseRoute
{

    protected string $prefix = 'tabungan';

    protected string $name = 'tabungan';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified', 'role:' . RoleEnum::Jamaah->keyValue()])->group(function ($route) {

            $route->get($this->prefix(''), [TabunganController::class, 'index'])->name('tabungan.index');

            $route->get($this->prefix('show/{saving_hash}'), [TabunganController::class, 'show'])->name('tabungan.show');
            $route->get($this->prefix('show/{saving_hash}/billing'), [TabunganController::class, 'billing'])->name('tabungan.billing');

        });
    }
}
