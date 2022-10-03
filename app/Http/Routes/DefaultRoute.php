<?php

namespace App\Http\Routes;

use Dentro\Yalr\BaseRoute;

class DefaultRoute extends BaseRoute
{

    protected string $prefix = '';

    protected string $name = '';

    /**
     * Register routes handled by this class.
     *
     * @return void
     */
    public function register(): void
    {
        $this->router->get('{path?}', function () {
            if (request()->expectsJson()) {
                return response()->json('Welcome to Multi School');
            }

            return 'Welcome to Multi School';
        })->where('path', '.*');
    }
}
