<?php

namespace App\Models\Spatie;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    const RoleSA = 'Super-Admin';
    const RoleAdmin = 'Admin';
}
