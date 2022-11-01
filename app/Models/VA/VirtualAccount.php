<?php

namespace App\Models\VA;

use App\Models\HashableId;
use App\Models\Plan\PlanPackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VirtualAccount extends Model
{
    use HasFactory, HashableId, SoftDeletes;

    protected $table = 'virtual_accounts';
    protected $fillable = [
        'tenant_id',
        'va_number',
        'va_label'
    ];


    public function vaable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    /**
     * Get the user that owns the VirtualAccount
     *
     * @return BelongsTo
     */
    public function myPackage(): BelongsTo
    {
        return $this->belongsTo(PlanPackage::class, 'package_id');
    }
}
