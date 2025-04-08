<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum FacilityPermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case FACILITY_INDEX = 'facility_index';
    case FACILITY_CREATE = 'facility_create';
    case FACILITY_SHOW = 'facility_show';
    case FACILITY_UPDATE = 'facility_update';
    case FACILITY_DELETE = 'facility_delete';

    public static function getGroupName(): string
    {
        return 'Facility Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }
}
