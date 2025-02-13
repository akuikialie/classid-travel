<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\TenantController;
use Dentro\Yalr\BaseRoute;

class TenantRoute extends BaseRoute
{
    protected string $prefix = 'travel';
    protected string $name = 'tenant';
    protected string $page = 'travel';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {

            $this->router->middleware(['role:super-administrator'])->group(function () {

                $this->router->post($this->prefix('datatable'), [TenantController::class, 'datatable'])
                    ->name($this->name('datatable'))->middleware(["permission:view {$this->page}"]);

                /* begin:: default route collection */

                $this->router->middleware(['quick_access:travel'])->group(function () {
                    $this->router->get($this->prefix(), [TenantController::class, 'index'])
                        ->name($this->name('index'));

                    $this->router->get($this->prefix('create'), [TenantController::class, 'create'])
                        ->name($this->name('create'));

                    $this->router->post($this->prefix(), [TenantController::class, 'store'])
                        ->name($this->name('store'));

                    $this->router->get($this->prefix('{tenant}/edit'), [TenantController::class, 'edit'])
                        ->name($this->name('edit'));

                    $this->router->put($this->prefix('{tenant}/update'), [TenantController::class, 'update'])
                        ->name($this->name('update'));

                    $this->router->delete($this->prefix('{tenant}'), [TenantController::class, 'destroy'])
                        ->name($this->name('destroy'));

                    $this->router->post($this->prefix('{tenant}/change-status'), [TenantController::class, 'changeStatus'])
                        ->name($this->name('change-status'));
                });

                /* end:: default route collection */
            });

            $this->router->middleware(['quick_access:role'])->group(function () {
                //                $this->router->get($this->prefix('profile/{tenant?}'),[TenantController::class, 'show'])
                //                    ->name($this->name('show'));

                $this->router->get($this->prefix('profile/{slug}'), [TenantController::class, 'showProfile'])
                    ->name($this->name('showProfile'));

                $this->router->put($this->prefix('profile/{tenant?}'), [TenantController::class, 'update'])
                    ->name($this->name('update.as-profile'));

                $this->router->post(
                    $this->prefix('add-media-collections/{tenant?}'),
                    [TenantController::class, 'addMedia']
                )
                    ->name($this->name('add-media'));

                $this->router->post($this->prefix('{tenant?}/change_theme'), [TenantController::class, 'changeTheme'])
                    ->name($this->name('changeTheme'));

                // $this->router->post($this->prefix('{tenant?}/auth_banner'), [TenantController::class, 'authBanner'])
                //     ->name($this->name('auth_banner'));

                $this->router->post(
                    $this->prefix('auth_banner/{tenant?}'),
                    [TenantController::class, 'authBanner']
                )
                    ->name($this->name('auth_banner'));
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
                    'icon' => 'fa-solid fa-globe',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->can('view travel') && $user->hasRole('super-administrator');
                },
            )
            ->route(
                name: 'admin.tenant.showProfile',
                title: 'Profil Travel',
                attribute: [
                    'icon' => 'fa-solid fa-globe',
                ],
                param: ['slug' => 'overview'],
                resolver: function () {
                    $user = \auth()->user();
                    return true;
                    return $user->can('view travel') && $user->tenant_id != null;
                },
            );
    }
}
