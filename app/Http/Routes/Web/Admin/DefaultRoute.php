<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Models\Spatie\Role;
use Dentro\Yalr\BaseRoute;

class DefaultRoute extends BaseRoute
{

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function () {
            $this->router->get('/dashboard',
                [DashboardController::class, 'index'])
                ->name($this->name('dashboard'));
        });
    }
}
