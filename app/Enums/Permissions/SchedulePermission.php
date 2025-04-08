<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum SchedulePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case SCHEDULE_INDEX = 'schedule_index';
    case SCHEDULE_CREATE = 'schedule_create';
    case SCHEDULE_SHOW = 'schedule_show';
    case SCHEDULE_UPDATE = 'schedule_update';
    case SCHEDULE_DELETE = 'schedule_delete';

    public static function getGroupName(): string
    {
        return 'Schedule Group';
    }

    public function usesOn(): string
    {
        return PermissionUsage::TENANT->value;
    }

    public function usesFor(): string
    {
        return match ($this) {
            self::SCHEDULE_INDEX, self::SCHEDULE_SHOW => 'view',
            self::SCHEDULE_CREATE => 'create',
            self::SCHEDULE_UPDATE => 'update',
            self::SCHEDULE_DELETE => 'delete',
        };
    }
}
