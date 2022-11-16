<?php

namespace App\Models\Spatie;

use App\Models\HashableId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory, SoftDeletes, HashableId;

    protected $fillable = [
        'tenant_id',
        'name',
        'guard_name',
        'type',
    ];
}
