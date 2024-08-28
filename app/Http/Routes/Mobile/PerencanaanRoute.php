<?php

namespace App\Http\Routes\Mobile;

use App\Enums\RoleEnum;
use App\Http\Controllers\Mobile\PerencanaanController;
use Dentro\Yalr\BaseRoute;

class PerencanaanRoute extends BaseRoute
{

    protected string $prefix = 'perencanaan';

    protected string $name = 'perencanaan';

    /**
     * @return void
     */
    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified', 'role:' . RoleEnum::Jamaah->keyValue()])->group(function ($route) {
            $route->get($this->prefix(''), [PerencanaanController::class, 'index'])->name('perencanaan.check-estimasi');
        });
    }
}
