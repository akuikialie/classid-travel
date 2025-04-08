<?php

namespace App\Enums\Permissions;

use App\Concerns\RBAC\PermissionEnumHelper;
use App\Contracts\RBAC\InteractsWithPermission;

enum SchedulePermission: string implements InteractsWithPermission
{
    use PermissionEnumHelper;

    // mandatory permission if implement CRUD
    case SCHEDULE_VIEW = 'schedule_view';
    case SCHEDULE_CREATE = 'schedule_create';
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
            self::SCHEDULE_VIEW => 'view',
            self::SCHEDULE_CREATE => 'create',
            self::SCHEDULE_UPDATE => 'update',
            self::SCHEDULE_DELETE => 'delete',
        };
    }
}
