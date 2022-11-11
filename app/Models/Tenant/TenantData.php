<?php

namespace App\Models\Tenant;

use App\Models\HashableId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantData extends Model
{
    use HasFactory, SoftDeletes, HashableId;
}
