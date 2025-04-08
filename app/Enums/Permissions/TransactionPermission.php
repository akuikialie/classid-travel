<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum TransactionPermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case TRANSACTION_VIEW = 'transaction_view';
    case TRANSACTION_CREATE = 'transaction_create';
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

    public function usesFor(): string
    {
        return match ($this) {
            self::TRANSACTION_VIEW => 'view',
            self::TRANSACTION_CREATE => 'create',
            self::TRANSACTION_UPDATE => 'update',
            self::TRANSACTION_DELETE => 'delete',
        };
    }
}
