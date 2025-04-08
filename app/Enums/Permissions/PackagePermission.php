<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum PackagePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case PACKAGE_INDEX = 'package_index';
    case PACKAGE_CREATE = 'package_create';
    case PACKAGE_SHOW = 'package_show';
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
}
