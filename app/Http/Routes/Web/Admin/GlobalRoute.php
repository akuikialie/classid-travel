<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Web\Admin\TenantController;
use Dentro\Yalr\BaseRoute;

class GlobalRoute extends BaseRoute
{

    protected string $prefix = 'tenant';
    protected string $name = 'tenant';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified', 'role:' . RoleEnum::Admin->keyValue()])->group(function () {
            $this->router->resource($this->prefix, TenantController::class);
            $this->router->post($this->prefix('{tenant}/add-media-collections'),
                [TenantController::class, 'addMedia'])
                ->name($this->name('tenant.add-media'));
        });
    }
}
