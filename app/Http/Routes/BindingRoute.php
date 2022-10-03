<?php

namespace App\Http\Routes;

use App\Models\User;
use Dentro\Yalr\Contracts\Bindable;
use Illuminate\Routing\Router;

class BindingRoute implements Bindable
{
    public function __construct(protected Router $router)
    {
        //
    }

    public function bind(): void
    {
        $this->router->bind('use_hash', fn ($value) => User::byHashOrFail($value));
    }
}
