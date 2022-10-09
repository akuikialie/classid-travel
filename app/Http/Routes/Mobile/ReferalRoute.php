<?php

namespace App\Http\Routes\Mobile;

use App\Http\Controllers\Mobile\HomeController;
use App\Http\Controllers\Mobile\ReferalController;
use Dentro\Yalr\BaseRoute;

class ReferalRoute extends BaseRoute
{

    protected string $prefix = 'invite';

    protected string $name = 'invite';

    public function register(): void
    {
        $this->router->get($this->prefix('{hash}/auth/{auth}'), [ReferalController::class, 'referal'])->name('invite.link');
        $this->router->middleware(['auth', 'verified'])->group(function(){
            $this->router->post($this->prefix('{hash}/auth/{auth}'), [ReferalController::class, 'referalAuth'])->name('invite.link-authentication');
            $this->router->post($this->prefix('saved'), [ReferalController::class, 'store'])->name('invite.saved');
            $this->router->post($this->prefix('store'), [ReferalController::class, 'store'])->name('invite.store');


            $this->router->post($this->prefix('auth/verified/referal/{referal}'), [ReferalController::class, 'authStore']);
        });
    }
}
