<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\UserController;
use Dentro\Yalr\BaseRoute;

class UserRoute extends BaseRoute
{
    protected string $prefix = 'user';
    protected string $name = 'user';
    protected string $page = 'user';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {

            $this->router->post($this->prefix('{type}/datatable'), [UserController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(["permission:view {$this->page}"]);

            $this->router->middleware(['quick_access:user'])->group(function () {
                $this->router->get($this->prefix('{type?}'), [UserController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('{type}/create'), [UserController::class, 'create'])
                    ->name($this->name('create'));
                $this->router->post($this->prefix(), [UserController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{user}/{slug}'), [UserController::class, 'show'])
                    ->name($this->name('show'));

                $this->router->get($this->prefix('{user}/edit'), [UserController::class, 'edit'])
                    ->name($this->name('edit'));
                $this->router->put($this->prefix('{user}/update'), [UserController::class, 'update'])
                    ->name($this->name('update'));
                $this->router->put($this->prefix('{user}/updatePassword'), [UserController::class, 'updatePassword'])
                    ->name($this->name('updatePassword'));
                $this->router->delete($this->prefix('{user}'), [UserController::class, 'destroy'])
                    ->name($this->name('destroy'));

                $this->router->post($this->prefix('{user}/change-status'), [UserController::class, 'changeStatus'])
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
        menus(group: 'setting')
            ->route(
                name: 'admin.user.index',
                title: 'Management Users',
                // param: [Auth::user()?->tenant_id ?? 0],
                attribute: [
                    'icon' => 'fa-solid fa-users',
                ],
                param: ['type' => 'staff'],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->hasAnyRole(['super-administrator', 'administrator']);
                },
            );

        menus(group: 'Travel')
            ->route(
                name: 'admin.user.index',
                title: 'Calon Jamaah',
                attribute: [
                    'icon' => 'fa-solid fa-users',
                ],
                param: ['type' => 'calon-jamaah'],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->hasAnyRole(['super-administrator', 'administrator']);
                },
            );
    }
}
