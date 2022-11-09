<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Web\Admin\TenantController;
use App\Http\Controllers\Web\Admin\UserController;
use Dentro\Yalr\BaseRoute;
use Illuminate\Support\Facades\Auth;

class UserRoute extends BaseRoute
{
    protected string $prefix = 'user';
    protected string $name = 'user';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function () {

            $allowRoles = [RoleEnum::SuperAdministrator->keyValue(), RoleEnum::Admin->keyValue()];

            $this->router->middleware(['role:' . implode('|', $allowRoles) ])->group(function (){
                $this->router->post($this->prefix('datatable'), [UserController::class, 'datatable'])
                    ->name($this->name('datatable'));
                $this->router->get($this->prefix(), [UserController::class, 'index'])
                    ->name($this->name('index'));
            });

        });
    }

    /**
     * Perform after register callback.
     *
     * @return void
     */
    public function afterRegister(): void
    {
         menus(group: 'Travel')
             ->route(
                 name: 'admin.user.index',
                 title: 'Management Users',
                 // param: [Auth::user()?->tenant_id ?? 0],
                 attribute: [
                     'icon' => 'fa-solid fa-users',
                 ],
                 resolver: function () {
                     $user = \auth()->user();
                     return $user->hasAnyRole([RoleEnum::SuperAdministrator->keyValue(), RoleEnum::Admin->keyValue()]);
                 },
             );
    }
}
