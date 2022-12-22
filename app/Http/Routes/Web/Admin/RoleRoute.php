<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\RoleController;
use Dentro\Yalr\BaseRoute;

class RoleRoute extends BaseRoute
{
    protected string $prefix = 'role';
    protected string $name = 'role';
    protected string $page = 'role';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function () {
            $this->router->post($this->prefix('datatable'), [RoleController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(["permission:view {$this->page}"]);

            $this->router->post($this->prefix('datatable/{role_hash}/users'), [RoleController::class, 'datatableRoleUsers'])
                ->name($this->name('datatable.role-user'))->middleware(["permission:view {$this->page}"]);

            /* begin:: default route collection */

            $this->router->middleware([ 'quick_access:role'])->group(function (){
                $this->router->get($this->prefix(), [RoleController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'), [RoleController::class, 'create'])
                    ->name($this->name('create'));
                $this->router->post($this->prefix(), [RoleController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{role_hash}/show'), [RoleController::class, 'show'])
                    ->name($this->name('show'));

                $this->router->get($this->prefix('{role_hash}/edit'), [RoleController::class, 'edit'])
                    ->name($this->name('edit'));
                $this->router->put($this->prefix('{role_hash}'), [RoleController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->delete($this->prefix('{role_hash}'), [RoleController::class, 'destroy'])
                    ->name($this->name('destroy'));

                $this->router->post($this->prefix('{role_hash}/change-status'),[RoleController::class, 'changeStatus'])
                    ->name($this->name('change-status'));
            });

            /* end:: default route collection */

        });
    }

    /**
     * Perform after register callback.
     *
     * @return void
     */
    public function afterRegister(): void
    {
         menus(group: 'setting')
             ->route(
                 name: 'admin.role.index',
                 title: 'Roles',
                 attribute: [
                     'icon' => 'fa-solid fa-users',
                 ],
                 resolver: function () {
                     $user = \auth()->user();
                     return $user->can('view role');
                 },
             );
    }
}
