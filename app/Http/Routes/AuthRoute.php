<?php

namespace App\Http\Routes;

use App\Http\Controllers\AuthController;
use Dentro\Yalr\BaseRoute;

class AuthRoute extends BaseRoute
{

    protected string $prefix = 'auth';

    protected string $name = 'auth';

    /**
     * Register routes handled by this class.
     *
     * @return void
     */
    public function register(): void
    {
        $this->router->post($this->prefix('login'), [AuthController::class, 'login']);

        $this->router->get($this->prefix('user'), [AuthController::class, 'user'])->middleware('auth:api');
    }
}
