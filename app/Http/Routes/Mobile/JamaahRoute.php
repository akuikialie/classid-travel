<?php

namespace App\Http\Routes\Mobile;

use App\Enums\RoleEnum;
use App\Http\Controllers\Mobile\JamaahController;
use Dentro\Yalr\BaseRoute;

class JamaahRoute extends BaseRoute
{

    protected string $prefix = 'jamaah';

    protected string $name = 'jamaah';

    public function register(): void
    {
        $this->router->middleware(['auth:sanctum', 'verified', 'role:' . RoleEnum::Jamaah->keyValue()])->group(function ($route) {

            $route->get($this->prefix(''), [JamaahController::class, 'index'])->name('jamaah.index');

        });
    }
}
