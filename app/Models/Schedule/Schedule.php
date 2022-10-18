<?php

namespace App\Models\Schedule;

use App\Models\Jamaah\Jamaah;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
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
