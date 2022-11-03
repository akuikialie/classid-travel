<?php

namespace App\Models\Spatie;

use App\Models\HashableId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, SoftDeletes, HashableId;

    const RoleSA = 'Super-Admin';
    const RoleAdmin = 'Admin';
}
