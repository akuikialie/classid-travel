<?php

namespace App\Enums\Permissions;

enum PermissionUsage: string
{
    case APP = 'app';
    case TENANT = 'tenant';
}
