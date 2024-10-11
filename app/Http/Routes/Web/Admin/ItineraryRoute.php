<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\Master\ItineraryController;
use Dentro\Yalr\BaseRoute;

class ItineraryRoute extends BaseRoute
{
    protected string $prefix = 'itinerary';
    protected string $name = 'itinerary';
    protected string $page = 'itinerary';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {
            $this->router->post($this->prefix('datatable'), [ItineraryController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(["permission:view {$this->page}"]);

            /* begin:: default route collection */

            $this->router->middleware([ "quick_access:{$this->page}"])->group(function (){
                $this->router->get($this->prefix(), [ItineraryController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'), [ItineraryController::class, 'create'])
                    ->name($this->name('create'));

                $this->router->post($this->prefix(), [ItineraryController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{itinerary}/edit'), [ItineraryController::class, 'edit'])
                    ->name($this->name('edit'));

                $this->router->put($this->prefix('{itinerary}'), [ItineraryController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->delete($this->prefix('{itinerary}'), [ItineraryController::class, 'destroy'])
                    ->name($this->name('destroy'));
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
                 name: 'admin.itinerary.index',
                 title: 'Master Kegiatan',
                 attribute: [
                     'icon' => 'bx bx-right-arrow-alt',
                 ],
                 resolver: function () {
                     $user = \auth()->user();
                     return $user->can('view itinerary') && $user->tenant_id != null;
                 },
             );
    }
}
