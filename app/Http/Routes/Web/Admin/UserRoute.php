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
            $this->router->middleware(['role:super-administrator|administrator'])->group(function (){
                $this->router->post($this->prefix('datatable'), [UserController::class, 'datatable'])
                    ->name($this->name('datatable'));
                $this->router->get($this->prefix(), [UserController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'), [UserController::class, 'create'])
                    ->name($this->name('create'));
                $this->router->post($this->prefix(), [UserController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{user_hash}/edit'), [UserController::class, 'edit'])
                    ->name($this->name('edit'));
                $this->router->put($this->prefix('{user_hash}'), [UserController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->delete($this->prefix('{user_hash}'), [UserController::class, 'destroy'])
                    ->name($this->name('destroy'));

                $this->router->post($this->prefix('{user_hash}/change-status'),[UserController::class, 'changeStatus'])
                    ->name($this->name('change-status'));
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
                     return $user->hasAnyRole(['super-administrator', 'administrator']);
                 },
             );
    }
}
