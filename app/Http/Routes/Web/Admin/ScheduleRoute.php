<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\Master\ScheduleController;
use Dentro\Yalr\BaseRoute;

class ScheduleRoute extends BaseRoute
{
    protected string $prefix = 'schedule';
    protected string $name = 'schedule';
    protected string $page = 'schedule';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function () {
            $this->router->post($this->prefix('datatable'), [ScheduleController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(["permission:view {$this->page}"]);

            /* begin:: default route collection */

            $this->router->middleware([ "quick_access:{$this->page}"])->group(function (){
                $this->router->get($this->prefix(), [ScheduleController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('create'), [ScheduleController::class, 'create'])
                    ->name($this->name('create'));

                $this->router->post($this->prefix(), [ScheduleController::class, 'store'])
                    ->name($this->name('store'));

                $this->router->get($this->prefix('{schedule_hash}/edit'), [ScheduleController::class, 'edit'])
                    ->name($this->name('edit'));

                $this->router->put($this->prefix('{schedule_hash}'), [ScheduleController::class, 'update'])
                    ->name($this->name('update'));

                $this->router->delete($this->prefix('{schedule_hash}'), [ScheduleController::class, 'destroy'])
                    ->name($this->name('destroy'));

                $this->router->post($this->prefix('{schedule_hash}/change-status'),[ScheduleController::class, 'changeStatus'])
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
                 name: 'admin.schedule.index',
                 title: 'Master Jadwal',
                 attribute: [
                     'icon' => 'bx bx-right-arrow-alt',
                 ],
                 resolver: function () {
                     $user = \auth()->user();
                     return $user->can('view schedule') && $user->tenant_id != null;
                 },
             );
    }
}
