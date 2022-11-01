<?php

namespace App\Models\Jamaah;

use App\Models\Plan\PlanPackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JamaahHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jamaah_histories';

    protected $fillable = [
        'tenant_id',
        'jamaah_id',
        'detail',
        'departure_status',
    ];

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id');
    }
}
