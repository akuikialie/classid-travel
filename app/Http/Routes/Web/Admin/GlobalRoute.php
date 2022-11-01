<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Web\Admin\TenantController;
use Dentro\Yalr\BaseRoute;

class GlobalRoute extends BaseRoute
{

    protected string $prefix = 'master';
    protected string $name = 'master';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified', 'role:' . RoleEnum::Admin->keyValue()])->group(function () {
            $this->router->resource('tenant', TenantController::class);
        });
    }
}
