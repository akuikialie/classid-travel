<?php

namespace App\Models\Spatie;

use App\Models\HashableId;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, SoftDeletes, HashableId, HasTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'guard_name',
        'type',
    ];

}
