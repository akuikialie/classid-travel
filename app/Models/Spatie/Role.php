<?php

namespace App\Models\Spatie;

use App\Models\HashableId;
use App\Models\Tenant\Tenant;
use App\Traits\HasTenant;
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
