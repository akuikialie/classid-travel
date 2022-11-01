<?php

namespace App\Http\Routes\Web\Admin;

use App\Http\Controllers\Web\Admin\AuthenticationSessionController;
use Dentro\Yalr\BaseRoute;
use Illuminate\Support\Facades\Request;

class AuthRoute extends BaseRoute
{

    protected string $prefix = 'auth';
    protected string $name = 'admin';

    // protected string $name = 'auth';

    /**
     * Register routes handled by this class.
     *
     * @return void
     */
    public function register(): void
    {
        $this->router->middleware(['guest'])->group(function(){
            /* login */
            $this->router->get($this->prefix('login'), [AuthenticationSessionController::class, 'create'])->name($this->name('login'));
            $this->router->post($this->prefix('login'), [AuthenticationSessionController::class, 'store'])->name($this->name('sign-in'));

            /* register */
            // $this->router->get($this->prefix('register'), [RegisterUserController::class, 'create'])->name('register');
            // $this->router->post($this->prefix('register'), [RegisterUserController::class, 'store'])->name('register');
        });


        // $this->router->get($this->prefix('user'), [AuthenticationSessionController::class, 'user'])->middleware('auth:api');

        $this->router->middleware(['auth', 'verified'])->group(function($route){
            $route->post($this->prefix('logout'), [AuthenticationSessionController::class, 'destroy'])->name($this->name('logout'));
        });
    }
}
