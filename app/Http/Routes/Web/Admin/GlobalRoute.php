<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Web\Admin\TenantController;
use Dentro\Yalr\BaseRoute;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Perform after register callback.
     *
     * @return void
     */
    public function afterRegister(): void
    {
        // menus(group: 'Travel')
        //     ->route(
        //         title: 'Profil Travel',
        //         name: 'tenant.show',
        //         param: [Auth::user()?->tenant_id ?? 0],
        //         attribute: [
        //             'icon' => 'bx bxs-dashboard',
        //         ],
        //     );
    }
}
