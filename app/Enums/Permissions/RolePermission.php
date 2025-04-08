<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum RolePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case ROLE_INDEX = 'role_index';
    case ROLE_CREATE = 'role_create';
    case ROLE_SHOW = 'role_show';
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

}
