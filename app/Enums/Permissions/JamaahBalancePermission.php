<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum JamaahBalancePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case JAMAAH_BALANCE_VIEW = 'jamaah-balance_view';
//    case JAMAAH_BALANCE_CREATE = 'jamaah-balance_create';
    case JAMAAH_BALANCE_UPDATE = 'jamaah-balance_update';
//    case JAMAAH_BALANCE_DELETE = 'jamaah-balance_delete';

    public static function getGroupName(): string
    {
        return 'Jamaah Balance Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }

    public function usesFor(): string
    {
        return match ($this) {
            self::JAMAAH_BALANCE_VIEW => 'view',
//            self::JAMAAH_BALANCE_CREATE => 'create',
            self::JAMAAH_BALANCE_UPDATE => 'update',
//            self::JAMAAH_BALANCE_DELETE => 'delete',
        };
    }
}
