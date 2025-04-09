<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\Permissions\FacilityPermission;
use App\Http\Controllers\Web\Admin\Master\FacilityController;
use Dentro\Yalr\BaseRoute;

class FacilityRoute extends BaseRoute
{
    protected string $prefix = 'facility';
    protected string $name = 'facility';
    protected string $page = 'facility';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {
            $this->router->post($this->prefix('datatable'), [FacilityController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(["permission:" . FacilityPermission::FACILITY_VIEW->value]);

            /* begin:: default route collection */

            $this->router->middleware(["quick_access:{$this->page}"])->group(function () {
                $this->router->get($this->prefix(), [FacilityController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'), [FacilityController::class, 'create'])
                    ->name($this->name('create'));

                $this->router->post($this->prefix(), [FacilityController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{facility}/edit'), [FacilityController::class, 'edit'])
                    ->name($this->name('edit'));

                $this->router->put($this->prefix('{facility}'), [FacilityController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->delete($this->prefix('{facility}'), [FacilityController::class, 'destroy'])
                    ->name($this->name('destroy'));

                $this->router->post($this->prefix('{facility}/change-status'), [FacilityController::class, 'changeStatus'])
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
                name: 'admin.facility.index',
                title: 'Master Fasilitas',
                attribute: [
                    'icon' => 'bx bx-right-arrow-alt',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return ($user->can(FacilityPermission::FACILITY_VIEW->value) && $user->tenant_id != null);
                },
            );
    }
}
