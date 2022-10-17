<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\Setup\DestinationController;
use App\Http\Controllers\Web\Admin\Setup\FacilityController;
use App\Http\Controllers\Web\Admin\Setup\PackageController;
use App\Http\Controllers\Web\Admin\Setup\ScheduleController;
use App\Models\Destination\Destination;
use App\Models\Spatie\Role;
use Dentro\Yalr\BaseRoute;

class SetupRoute extends BaseRoute
{


    protected string $prefix = 'setup';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified', 'role:' . Role::RoleSA])->group(function () {

            $this->router->resource($this->prefix('destination'), DestinationController::class, [
                'names' => [
                    'index' => "{$this->prefix}.destination.index",
                    'create' => "{$this->prefix}.destination.create",
                    'store' => "{$this->prefix}.destination.store",
                    'show' => "{$this->prefix}.destination.show",
                    'edit' => "{$this->prefix}.destination.edit",
                    'update' => "{$this->prefix}.destination.update",
                    'destroy' => "{$this->prefix}.destination.destroy",
                ]
            ]);

            $this->router->resource($this->prefix('facility'), FacilityController::class, [
                'names' => [
                    'index' => "{$this->prefix}.facility.index",
                    'create' => "{$this->prefix}.facility.create",
                    'store' => "{$this->prefix}.facility.store",
                    'show' => "{$this->prefix}.facility.show",
                    'edit' => "{$this->prefix}.facility.edit",
                    'update' => "{$this->prefix}.facility.update",
                    'destroy' => "{$this->prefix}.facility.destroy",
                ]
            ]);

            $this->router->resource($this->prefix('package'), PackageController::class, [
                'names' => [
                    'index' => "{$this->prefix}.package.index",
                    'create' => "{$this->prefix}.package.create",
                    'store' => "{$this->prefix}.package.store",
                    'show' => "{$this->prefix}.package.show",
                    'edit' => "{$this->prefix}.package.edit",
                    'update' => "{$this->prefix}.package.update",
                    'destroy' => "{$this->prefix}.package.destroy",
                ]
            ]);

            $this->router->resource($this->prefix('schedule'), ScheduleController::class, [
                'names' => [
                    'index' => "{$this->prefix}.schedule.index",
                    'create' => "{$this->prefix}.schedule.create",
                    'store' => "{$this->prefix}.schedule.store",
                    'show' => "{$this->prefix}.schedule.show",
                    'edit' => "{$this->prefix}.schedule.edit",
                    'update' => "{$this->prefix}.schedule.update",
                    'destroy' => "{$this->prefix}.schedule.destroy",
                ]
            ]);

            // $this->router->get('/dashboard', function () {
            //     return view('pages.web.dashboard.dashboard-index');
            // })->name('dashboard.admin');
        });
    }
}
