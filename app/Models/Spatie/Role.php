<?php

namespace App\Models\Spatie;

use App\Models\Tenant\Tenant;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\PermissionRegistrar;
use Veelasky\LaravelHashId\Eloquent\HashableId;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string $guard_name
 * @property string $type
 * @property string $label
 * @property Tenant $tenant
 * */
class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, SoftDeletes, HashableId, HasTenant;

    protected $table = 'rbac_roles';

    protected $fillable = [
        'tenant_id',
        'name',
        'guard_name',
        'type',
        'label',
    ];

    /** @inheritdoc */
    // public function users(): MorphToMany
    // {
    //     return $this->morphedByMany(
    //         getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
    //         'model',
    //         config('permission.table_names.model_has_roles'),
    //         app(PermissionRegistrar::class)->pivotRole,
    //         config('permission.column_names.model_morph_key')
    //     );
    // }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
