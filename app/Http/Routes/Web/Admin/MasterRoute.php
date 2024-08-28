<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Web\Admin\Master\ItineraryController;
use Dentro\Yalr\BaseRoute;

class MasterRoute extends BaseRoute
{

    protected string $prefix = 'master';
    protected string $name = 'master';
    protected string $page = 'master';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified', 'role:' . RoleEnum::Admin->keyValue()])->group(function () {

           /* $this->router->resource($this->prefix('destination'), DestinationController::class, [
                'names' => [
                    'index' => $this->name('destination.index'),
                    'create' => $this->name('destination.create'),
                    'store' => $this->name('destination.store'),
                    'show' => $this->name('destination.show'),
                    'edit' => $this->name('destination.edit'),
                    'update' => $this->name('destination.update'),
                    'destroy' => $this->name('destination.destroy'),
                ]
            ]);*/

            /*$this->router->resource($this->prefix('facility'), FacilityController::class, [
                'names' => [
                    'index' => $this->name('facility.index'),
                    'create' => $this->name('facility.create'),
                    'store' => $this->name('facility.store'),
                    'show' => $this->name('facility.show'),
                    'edit' => $this->name('facility.edit'),
                    'update' => $this->name('facility.update'),
                    'destroy' => $this->name('facility.destroy'),
                ]
            ]);*/

            /*$this->router->resource($this->prefix('package'), PackageController::class, [
                'names' => [
                    'index' => $this->name('package.index'),
                    'create' => $this->name('package.create'),
                    'store' => $this->name('package.store'),
                    'show' => $this->name('package.show'),
                    'edit' => $this->name('package.edit'),
                    'update' => $this->name('package.update'),
                    'destroy' => $this->name('package.destroy'),
                ]
            ]);*/
            /*$this->router->get($this->prefix('package/{package}/itinerary-setup'),
                [PackageController::class, 'createSetupItinerary'])
                ->name($this->name('package.itinerary-setup.create'));
            $this->router->post($this->prefix('package/{package}/itinerary-setup'),
                [PackageController::class, 'storeSetupItinerary'])
                ->name($this->name('package.itinerary-setup.store'));*/

//            $this->router->resource($this->prefix('schedule'), ScheduleController::class)->names($this->name('schedule'));

            $this->router->resource($this->prefix('itinerary'), ItineraryController::class, [
                'names' => [
                    'index' => $this->name('itinerary.index'),
                    'create' => $this->name('itinerary.create'),
                    'store' => $this->name('itinerary.store'),
                    'show' => $this->name('itinerary.show'),
                    'edit' => $this->name('itinerary.edit'),
                    'update' => $this->name('itinerary.update'),
                    'destroy' => $this->name('itinerary.destroy'),
                ]
            ]);


            // $this->router->get('/dashboard', function () {
            //     return view('pages.web.dashboard.dashboard-index');
            // })->name('dashboard.admin');
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
           /* ->route(
                name: 'admin.master.package.index',
                title: 'Master Paket',
                attribute: [
                    'icon' => 'bx bx-right-arrow-alt',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->hasRole(RoleEnum::Admin->keyValue());
                },
            )*/
        /*    ->route(
                name: 'admin.master.facility.index',
                title: 'Master Fasilitas',
                attribute: [
                    'icon' => 'bx bx-right-arrow-alt',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->hasRole(RoleEnum::Admin->keyValue());
                },
            )*/->route(
                name: 'admin.master.itinerary.index',
                title: 'Master Kegiatan',
                attribute: [
                    'icon' => 'bx bx-right-arrow-alt',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->hasRole(RoleEnum::Admin->keyValue());
                },
            );
    }
}
