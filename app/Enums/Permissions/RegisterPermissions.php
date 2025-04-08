<?php

namespace App\Enums\Permissions;

enum RegisterPermissions: string
{

    // mandatory permission if implement CRUD
    case TRAVELS = TravelPermission::class;

    case PACKAGES = PackagePermission::class;
    case DESTINATIONS = DestinationPermission::class;
    case FACILITIES = FacilityPermission::class;
    case ITINERARIES = ItineraryPermission::class;
    case SCHEDULES = SchedulePermission::class;

    case USERS = UserPermission::class;
    case ROLES = RolePermission::class;
    case TRANSACTIONS = TransactionPermission::class;
    case JAMAAH_BALANCES = JamaahBalancePermission::class;


    public function usingOnPage(): string
    {
        return match ($this) {
            self::ROLES => 'role',
            self::USERS => 'user',
            self::DESTINATIONS => 'destination',
            self::FACILITIES => 'facility',
            self::ITINERARIES => 'itinerary',
            self::JAMAAH_BALANCES => 'jamaah-balance',
            self::PACKAGES => 'package',
            self::SCHEDULES => 'schedule',
            self::TRANSACTIONS => 'transaction',
            self::TRAVELS => 'travel',

        };
    }

    public static function permissionShortName(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
            'export',
            'import',
        ];
    }
}
