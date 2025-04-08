<?php

namespace App\Http\Routes\Web\Admin;

use App\Enums\Permissions\TransactionPermission;
use App\Http\Controllers\Web\Admin\TransactionController;
use Dentro\Yalr\BaseRoute;

class TransactionRoute extends BaseRoute
{
    protected string $prefix = 'transaction';
    protected string $name = 'transaction';
    protected string $page = 'transaction';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified'])->group(function () {

            $this->router->post($this->prefix('datatable'), [TransactionController::class, 'datatable'])
                ->name($this->name('datatable'))->middleware(['permission:' . TransactionPermission::TRANSACTION_VIEW->value]);

            /* begin:: default route collection */

            $this->router->middleware(["quick_access:{$this->page}"])->group(function () {
                $this->router->get($this->prefix(), [TransactionController::class, 'index'])
                    ->name($this->name('index'));

                $this->router->post($this->prefix('download'), [TransactionController::class, 'download'])
                    ->name($this->name('download'));

//                $this->router->get($this->prefix('{transaction}/detail'), [TransactionController::class, 'detail'])
//                    ->name($this->name('detail'));
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
                name: 'admin.transaction.index',
                title: 'Transaksi',
                attribute: [
                    'icon' => 'fa-solid fa-money',
                ],
                resolver: function () {
                    $user = \auth()->user();
                    return $user->can(TransactionPermission::TRANSACTION_VIEW->value);
                },
            );
    }
}
