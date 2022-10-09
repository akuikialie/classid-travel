<?php

namespace App\Http\Routes\Mobile;

use App\Http\Controllers\Mobile\ProfileController;
use Dentro\Yalr\BaseRoute;

class ProfileRoute extends BaseRoute
{

    protected string $prefix = 'profile';

    protected string $name = 'profile';

    public function register(): void
    {
        $this->router->middleware(['auth', 'verified'])->group(function ($route) {

            $route->get($this->prefix(''), [ProfileController::class, 'index'])->name('profile.index');

            $route->get($this->prefix('edit/{user}'), [ProfileController::class, 'edit'])->name('profile.edit');

            $route->put($this->prefix('edit/{user}/edit-information'), [ProfileController::class, 'editInformation'])->name('profile.edit-information');
            $route->put($this->prefix('edit/{user}/update-password'), [ProfileController::class, 'updatePassword'])->name('profile.update-password');
        });
    }
}
