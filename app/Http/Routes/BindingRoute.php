<?php

namespace App\Http\Routes;

use App\Models\Tenant\Tenant;
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
        $this->router->bind('tenant_hash', fn ($value) => Tenant::byHashOrFail($value));
        $this->router->bind('user_hash', fn ($value) => User::byHashOrFail($value));
//        $this->router->bind('tenant_with_trash_hash', fn ($value) => Tenant::query()->withTrashed()->byHashOrFail($value));
    }
}
