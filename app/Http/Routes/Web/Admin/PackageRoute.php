<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\Permissions\PackagePermission;
use App\Http\Controllers\Web\Admin\Master\PackageController;
use Dentro\Yalr\BaseRoute;

class PackageRoute extends BaseRoute
{
    protected string $prefix = 'package';
    protected string $name = 'package';
    protected string $page = 'package';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {
            $this->router->post($this->prefix('datatable'), [PackageController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(['permission:' . PackagePermission::PACKAGE_VIEW->value]);

            /* begin:: default route collection */

            $this->router->middleware(["quick_access:{$this->page}"])->group(function () {
                $this->router->get($this->prefix(), [PackageController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'), [PackageController::class, 'create'])
                    ->name($this->name('create'));
                $this->router->post($this->prefix(), [PackageController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{package}/edit'), [PackageController::class, 'edit'])
                    ->name($this->name('edit'));
                $this->router->put($this->prefix('{package}'), [PackageController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->delete($this->prefix('{package}'), [PackageController::class, 'destroy'])
                    ->name($this->name('destroy'));

                $this->router->post($this->prefix('{package}/change-status'), [PackageController::class, 'changeStatus'])
                    ->name($this->name('change-status'));

                $this->router->get($this->prefix('{package}/itinerary-setup'),
                    [PackageController::class, 'createSetupItinerary'])
                    ->name($this->name('itinerary-setup.create'));

                $this->router->post($this->prefix('{package}/itinerary-setup'),
                    [PackageController::class, 'storeSetupItinerary'])
                    ->name($this->name('itinerary-setup.store'));
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
                name: 'admin.package.index',
                title: 'Master Paket',
                attribute: [
                    'icon' => 'bx bx-right-arrow-alt',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->can(PackagePermission::PACKAGE_VIEW->value) && $user->tenant_id != null;
                },
            );
    }
}
