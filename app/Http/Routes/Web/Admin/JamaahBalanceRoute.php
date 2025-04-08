<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\Permissions\JamaahBalancePermission;
use App\Http\Controllers\Web\Admin\JamaahBalanceController;
use Dentro\Yalr\BaseRoute;

class JamaahBalanceRoute extends BaseRoute
{
    protected string $prefix = 'jamaah-balance';
    protected string $name = 'jamaah-balance';
    protected string $page = 'jamaah-balance';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {

            $this->router->post($this->prefix('datatable'), [JamaahBalanceController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(['permission:' . JamaahBalancePermission::JAMAAH_BALANCE_VIEW->value]);
            /* begin:: default route collection */

            $this->router->middleware(["quick_access:{$this->page}"])->group(function () {
                $this->router->get($this->prefix(), [JamaahBalanceController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->get($this->prefix('{virtualAccount}/balance-exchange'), [JamaahBalanceController::class, 'convertBalanceView'])
                    ->name($this->name('balance-exchange'));

                $this->router->put($this->prefix('{virtualAccount}/balance-exchange'), [JamaahBalanceController::class, 'convertBalance'])
                    ->name($this->name('balance-exchange'));
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
        menus(group: 'Transaksi')
            ->route(
                name: 'admin.jamaah-balance.index',
                title: 'Saldo Jamaah',
                attribute: [
                    'icon' => 'fa-solid fa-money',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->can(JamaahBalancePermission::JAMAAH_BALANCE_VIEW->value);
                },
            );
    }
}
