<?php

namespace App\Http\Routes\Mobile;

use App\Enums\RoleEnum;
use App\Http\Controllers\Mobile\ReferralController;
use Dentro\Yalr\BaseRoute;

class ReferralRoute extends BaseRoute
{

    protected string $prefix = 'invite';

    protected string $name = 'invite';

    public function register(): void
    {
        $this->router->get($this->prefix('{hash}/auth/{auth}'), [ReferralController::class, 'referral'])->name('invite.link');
        $this->router->post($this->prefix('{hash}/auth/{auth}'), [ReferralController::class, 'referralAuth'])->name('invite.link-authentication');
        $this->router->middleware(['auth:sanctum', 'verified', 'role:' . RoleEnum::Jamaah->keyValue()])->group(function(){
            $this->router->post($this->prefix('store'), [ReferralController::class, 'store'])->name('invite.store');
        });
    }
}
