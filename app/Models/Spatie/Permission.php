<?php

namespace App\Models\Spatie;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $group
 * @property string $name
 * @property string $guard_name
 * @property string $type
 * @property string $label
 * */
class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

    protected $fillable = [
        'tenant_id',
        'group',
        'name',
        'guard_name',
        'type',
        'label',
    ];
}
