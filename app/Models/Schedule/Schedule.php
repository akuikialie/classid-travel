<?php

namespace App\Models\Schedule;

use App\Models\Jamaah\Jamaah;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory, HasTenant;

    protected $table = 'schedules';

    protected $fillable = [
        'tenant_id',
        'departure_date',
    ];

    /**
     * Get all of the comments for the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jamaah(): HasMany
    {
        return $this->hasMany(Jamaah::class, 'schedule_id');
    }
}
