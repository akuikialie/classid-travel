<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\Permissions\DestinationPermission;
use App\Http\Controllers\Web\Admin\Master\DestinationController;
use Dentro\Yalr\BaseRoute;

class DestinationRoute extends BaseRoute
{
    protected string $prefix = 'destination';
    protected string $name = 'destination';
    protected string $page = 'destination';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {
            $this->router->post($this->prefix('datatable'), [DestinationController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(["permission:" . DestinationPermission::DESTINATION_VIEW->value]);

            /* begin:: default route collection */

            $this->router->middleware(['quick_access:' . $this->page])->group(function () {
                $this->router->get($this->prefix(), [DestinationController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'), [DestinationController::class, 'create'])
                    ->name($this->name('create'));
                $this->router->post($this->prefix(), [DestinationController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{destination}/edit'), [DestinationController::class, 'edit'])
                    ->name($this->name('edit'));
                $this->router->put($this->prefix('{destination}'), [DestinationController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->delete($this->prefix('{destination}'), [DestinationController::class, 'destroy'])
                    ->name($this->name('destroy'));

                $this->router->post($this->prefix('{destination}/change-status'), [DestinationController::class, 'changeStatus'])
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
        menus(group: 'Master')
            ->route(
                name: 'admin.destination.index',
                title: 'Master Tujuan',
                attribute: [
                    'icon' => 'bx bx-right-arrow-alt',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return ($user->can(DestinationPermission::DESTINATION_VIEW->value) && $user->tenant_id != null);
                },
            );
    }
}
