<?php

namespace App\Http\Routes\Mobile;

use App\Enums\RoleEnum;
use App\Http\Controllers\Mobile\PerencanaanController;

class PerencanaanRoute extends \Dentro\Yalr\BaseRoute
{

    protected string $prefix = 'perencanaan';

    protected string $name = 'perencanaan';
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->router->middleware(['auth', 'verified', 'role:' . RoleEnum::Jamaah->keyValue()])->group(function ($route) {
            $route->get($this->prefix(''), [PerencanaanController::class, 'index'])->name('perencanaan.check-estimasi');
        });
    }
}
