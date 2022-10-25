<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\Master\DestinationController;
use App\Http\Controllers\Web\Admin\Master\FacilityController;
use App\Http\Controllers\Web\Admin\Master\PackageController;
use App\Http\Controllers\Web\Admin\Master\ScheduleController;
use App\Models\Spatie\Role;
use Dentro\Yalr\BaseRoute;

class MasterRoute extends BaseRoute
{

    protected string $prefix = 'master';
    protected string $name = 'master';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified', 'role:' . Role::RoleSA])->group(function () {

            $this->router->resource($this->prefix('destination'), DestinationController::class, [
                'names' => [
                    'index' => $this->name('destination.index'),
                    'create' => $this->name('destination.create'),
                    'store' => $this->name('destination.store'),
                    'show' => $this->name('destination.show'),
                    'edit' => $this->name('destination.edit'),
                    'update' => $this->name('destination.update'),
                    'destroy' => $this->name('destination.destroy'),
                ]
            ]);

            $this->router->resource($this->prefix('facility'), FacilityController::class, [
                'names' => [
                    'index' => $this->name('facility.index'),
                    'create' => $this->name('facility.create'),
                    'store' => $this->name('facility.store'),
                    'show' => $this->name('facility.show'),
                    'edit' => $this->name('facility.edit'),
                    'update' => $this->name('facility.update'),
                    'destroy' => $this->name('facility.destroy'),
                ]
            ]);

            $this->router->resource($this->prefix('package'), PackageController::class, [
                'names' => [
                    'index' => $this->name('package.index'),
                    'create' => $this->name('package.create'),
                    'store' => $this->name('package.store'),
                    'show' => $this->name('package.show'),
                    'edit' => $this->name('package.edit'),
                    'update' => $this->name('package.update'),
                    'destroy' => $this->name('package.destroy'),
                ]
            ]);

            $this->router->resource($this->prefix('schedule'), ScheduleController::class, [
                'names' => [
                    'index' => $this->name('schedule.index'),
                    'create' => $this->name('schedule.create'),
                    'store' => $this->name('schedule.store'),
                    'show' => $this->name('schedule.show'),
                    'edit' => $this->name('schedule.edit'),
                    'update' => $this->name('schedule.update'),
                    'destroy' => $this->name('schedule.destroy'),
                ]
            ]);

            // $this->router->get('/dashboard', function () {
            //     return view('pages.web.dashboard.dashboard-index');
            // })->name('dashboard.admin');
        });
    }
}
