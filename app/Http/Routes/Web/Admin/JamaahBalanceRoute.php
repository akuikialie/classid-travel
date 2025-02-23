<?php

namespace App\Http\Routes\Web\Admin;

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
                ->name($this->name('datatable'))->middleware(/*["permission:view {$this->page}"]*/);
            /* begin:: default route collection */

            $this->router->middleware([])->group(function () {
                $this->router->get($this->prefix(), [JamaahBalanceController::class, 'index'])
                    ->name($this->name('index'));
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
                    return true;
                    $user = \auth()->user();
                    return $user->can('view transaction');
                },
            );
    }
}
