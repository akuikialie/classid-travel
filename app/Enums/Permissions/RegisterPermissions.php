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
    case TRAVEL = TravelPermission::class;
}
