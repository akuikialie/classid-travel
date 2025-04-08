<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum DestinationPermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case DESTINATION_VIEW = 'destination_view';
    case DESTINATION_CREATE = 'destination_create';
    case DESTINATION_UPDATE = 'destination_update';
    case DESTINATION_DELETE = 'destination_delete';

    public static function getGroupName(): string
    {
        return 'Destination Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }

    public function usesFor(): string
    {
        return match ($this) {
            self::DESTINATION_VIEW => 'view',
            self::DESTINATION_CREATE => 'create',
            self::DESTINATION_UPDATE => 'update',
            self::DESTINATION_DELETE => 'delete',
        };
    }
}
