<?php

namespace App\Models\Spatie;

use App\Models\HashableId;
use App\Traits\HasTenant;
use App\Models\Spatie\Tenant\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, SoftDeletes, HashableId, HasTenant;

    protected $table = 'rbac_roles';

    protected $fillable = [
        'tenant_id',
        'name',
        'guard_name',
        'type',
    ];


}
