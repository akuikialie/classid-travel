<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Web\Admin\TenantController;
use Dentro\Yalr\BaseRoute;
use Illuminate\Support\Facades\Auth;

class TenantRoute extends BaseRoute
{
    protected string $prefix = 'tenant';
    protected string $name = 'tenant';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function () {

            $this->router->middleware(['role:super-administrator'])->group(function (){
                $this->router->post($this->prefix('datatable'),[TenantController::class, 'datatable'])
                    ->name($this->name('datatable'));

                $this->router->get($this->prefix(),[TenantController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'),[TenantController::class, 'create'])
                    ->name($this->name('create'));

                $this->router->post($this->prefix(),[TenantController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{tenant_hash}/edit'),[TenantController::class, 'edit'])
                    ->name($this->name('edit'));

                $this->router->put($this->prefix('{tenant_hash}/update'),[TenantController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->post($this->prefix('{tenant_hash}/change-status'),[TenantController::class, 'changeStatus'])
                    ->name($this->name('change-status'));

                $this->router->delete($this->prefix('{tenant_hash}'),[TenantController::class, 'destroy'])
                    ->name($this->name('destroy'));
            });

            $this->router->middleware(['role:administrator'])->group(function (){
                $this->router->get($this->prefix('profile/{tenant_hash?}'),[TenantController::class, 'show'])
                    ->name($this->name('show'));

                $this->router->put($this->prefix('profile/update/{tenant_hash?}'),[TenantController::class, 'update'])
                    ->name($this->name('update.as-profile'));

                $this->router->post($this->prefix('add-media-collections/{tenant_hash?}'),
                    [TenantController::class, 'addMedia'])
                    ->name($this->name('add-media'));
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
                 name: 'admin.tenant.index',
                 title: 'Travel',
                 // param: [Auth::user()?->tenant_id ?? 0],
                 attribute: [
                     'icon' => 'fa-solid fa-plane',
                 ],
                 resolver: function () {
                     $user = \auth()->user();
                     return $user->hasRole('super-administrator');
                 },
             )
             ->route(
                 name: 'admin.tenant.show',
                 title: 'Profil Travel',
                 attribute: [
                     'icon' => 'bx bxs-dashboard',
                 ],
                 resolver: function () {
                     $user = \auth()->user();
                     return $user->hasRole('administrator');
                 },
             );
    }
}
