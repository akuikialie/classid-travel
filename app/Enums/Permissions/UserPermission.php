<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum UserPermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case USER_INDEX = 'user_index';
    case USER_CREATE = 'user_create';
    case USER_SHOW = 'user_show';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';

    public static function getGroupName(): string
    {
        return 'User Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }

    public function usesFor(): string
    {
        return match ($this) {
            self::USER_INDEX, self::USER_SHOW => 'view',
            self::USER_CREATE => 'create',
            self::USER_UPDATE => 'update',
            self::USER_DELETE => 'delete',
        };
    }
}
