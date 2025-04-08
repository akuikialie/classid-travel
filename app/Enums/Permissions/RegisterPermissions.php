<?php

namespace App\Enums\Permissions;

enum RegisterPermissions: string
{

    // mandatory permission if implement CRUD
    case ROLES = RolePermission::class;
    case USERS = UserPermission::class;
    case DESTINATIONS = DestinationPermission::class;
    case FACILITIES = FacilityPermission::class;
    case ITINERARIES = ItineraryPermission::class;
    case JAMAAH_BALANCES = JamaahBalancePermission::class;
    case PACKAGES = PackagePermission::class;
    case SCHEDULES = SchedulePermission::class;
    case TRANSACTIONS = TransactionPermission::class;
    case TRAVELS = TravelPermission::class;

    public function usingOnPage(): string
    {
        return match ($this) {
            self::ROLES => 'roles',
            self::USERS => 'users',
            self::DESTINATIONS => 'destinations',
            self::FACILITIES => 'facilities',
            self::ITINERARIES => 'itineraries',
            self::JAMAAH_BALANCES => 'jamaah_balances',
            self::PACKAGES => 'packages',
            self::SCHEDULES => 'schedules',
            self::TRANSACTIONS => 'transactions',
            self::TRAVELS => 'travel',

        };
    }
}
