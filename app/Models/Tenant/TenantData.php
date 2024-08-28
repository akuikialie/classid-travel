<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantData extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'key', 'value', 'option', 'is_active', 'tenant_id'
    ];
}
