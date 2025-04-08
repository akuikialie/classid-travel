<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum TravelPermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case TRAVEL_INDEX = 'travel_index';
    case TRAVEL_CREATE = 'travel_create';
    case TRAVEL_SHOW = 'travel_show';
    case TRAVEL_UPDATE = 'travel_update';
    case TRAVEL_DELETE = 'travel_delete';

    public static function getGroupName(): string
    {
        return 'Travel Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }
}
