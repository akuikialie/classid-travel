<?php

namespace App\Http\Routes;

use App\Models\Destination\Destination;
use App\Models\Itinerary\ItineraryActivity;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Models\Schedule\Schedule;
use App\Models\Spatie\Role;
use App\Models\Tenant\Tenant;
use App\Models\User;
use App\Models\VA\VirtualAccount;
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
        $this->router->bind('itinerary_hash', fn ($value) => ItineraryActivity::query()
            ->withCount(['hasItineraries'])
            ->byHashOrFail($value));
        $this->router->bind('schedule_hash', fn ($value) => Schedule::query()
            ->withCount(['jamaah'])
            ->byHashOrFail($value));
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
            ->with(['permissions', 'users'])
            ->withCount(['permissions', 'users'])
            ->byHashOrFail($value));

        /* begin:: mobile route binding */
        $this->router->bind('saving_hash', fn ($value) => VirtualAccount::query()
            ->with(['myPackage'])
            ->byHashOrFail($value));
        /* end:: mobile route binding */
    }
}
