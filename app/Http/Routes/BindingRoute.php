<?php

namespace App\Http\Routes;

use App\Models\Destination\Destination;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Models\Spatie\Role;
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
        $this->router->bind('facility_hash', fn ($value) => PlanFacility::query()
            ->withCount(['packages'])
            ->byHashOrFail($value));
        $this->router->bind('destination_hash', fn ($value) => Destination::query()
            ->with(['myAddress'])
            ->withCount(['packages'])
            ->byHashOrFail($value));
        $this->router->bind('package_hash', fn ($value) => PlanPackage::query()
            ->withCount(['jamaah', 'myDestinations', 'myFacilities'])
            ->byHashOrFail($value));
        $this->router->bind('role_hash', fn ($value) => Role::query()
            ->withCount(['permissions', 'users'])
            ->byHashOrFail($value));
//        $this->router->bind('tenant_with_trash_hash', fn ($value) => Tenant::query()->withTrashed()->byHashOrFail($value));
    }
}
