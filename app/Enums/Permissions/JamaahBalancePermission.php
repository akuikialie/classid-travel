<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum JamaahBalancePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case JAMAAH_BALANCE_INDEX = 'jamaah_balance_index';
    case JAMAAH_BALANCE_CREATE = 'jamaah_balance_create';
    case JAMAAH_BALANCE_SHOW = 'jamaah_balance_show';
    case JAMAAH_BALANCE_UPDATE = 'jamaah_balance_update';
    case JAMAAH_BALANCE_DELETE = 'jamaah_balance_delete';

    public static function getGroupName(): string
    {
        return 'Jamaah Balance Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }
}
