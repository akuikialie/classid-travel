<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum RolePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case ROLE_VIEW = 'role_view';
    case ROLE_CREATE = 'role_create';
    case ROLE_UPDATE = 'role_update';
    case ROLE_DELETE = 'role_delete';

    public static function getGroupName(): string
    {
        return 'Role Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }

    public function usesFor(): string
    {
        return match ($this) {
            self::ROLE_VIEW => 'view',
            self::ROLE_CREATE => 'create',
            self::ROLE_UPDATE => 'update',
            self::ROLE_DELETE => 'delete',
        };
    }

}
