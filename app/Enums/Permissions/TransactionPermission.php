<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum TransactionPermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case TRANSACTION_INDEX = 'transaction_index';
    case TRANSACTION_CREATE = 'transaction_create';
    case TRANSACTION_SHOW = 'transaction_show';
    case TRANSACTION_UPDATE = 'transaction_update';
    case TRANSACTION_DELETE = 'transaction_delete';

    public static function getGroupName(): string
    {
        return 'Transaction Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }
}
