<?php

namespace App\Models\Jamaah;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JamaahHistory extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

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
