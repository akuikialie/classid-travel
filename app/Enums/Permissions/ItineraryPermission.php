<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum ItineraryPermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case ITINERARY_VIEW = 'itinerary_view';
    case ITINERARY_CREATE = 'itinerary_create';
    case ITINERARY_UPDATE = 'itinerary_update';
    case ITINERARY_DELETE = 'itinerary_delete';

    public static function getGroupName(): string
    {
        return 'Itinerary Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }

    public function usesFor(): string
    {
        return match ($this) {
            self::ITINERARY_VIEW => 'view',
            self::ITINERARY_CREATE => 'create',
            self::ITINERARY_UPDATE => 'update',
            self::ITINERARY_DELETE => 'delete',
        };
    }
}
