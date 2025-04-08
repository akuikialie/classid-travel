<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum PackagePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case PACKAGE_VIEW = 'package_view';
    case PACKAGE_CREATE = 'package_create';
    case PACKAGE_UPDATE = 'package_update';
    case PACKAGE_DELETE = 'package_delete';

    public static function getGroupName(): string
    {
        return 'Package Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }

    public function usesFor(): string
    {
        return match ($this) {
            self::PACKAGE_VIEW => 'view',
            self::PACKAGE_CREATE => 'create',
            self::PACKAGE_UPDATE => 'update',
            self::PACKAGE_DELETE => 'delete',
        };
    }
}
