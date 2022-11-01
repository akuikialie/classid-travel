<?php

namespace App\Http\Routes\Mobile;

use App\Enums\RoleEnum;
use App\Http\Controllers\Mobile\PackageController;
use Dentro\Yalr\BaseRoute;

class PackageRoute extends BaseRoute
{

    protected string $prefix = 'package';

    protected string $name = 'package';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified', 'role:' . RoleEnum::Jamaah->keyValue()])->group(function ($route) {

            $route->get($this->prefix(''), [PackageController::class, 'index'])->name('package.index');
            $route->get($this->prefix('{package}/show'), [PackageController::class, 'show'])->name('package.show');

            $route->post($this->prefix('add-to-jamaah/{package}'), [PackageController::class, 'addPackageToJamaah'])->name('package.add-to-jamaah');

        });
    }
}
