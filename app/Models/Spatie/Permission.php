<?php

namespace App\Models\Spatie;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

    protected $fillable = [
        'tenant_id', 'group',
        'name', 'guard_name',
        'type', 'label',
    ];
}
