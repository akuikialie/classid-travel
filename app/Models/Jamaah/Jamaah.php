<?php

namespace App\Models\Jamaah;

use App\Models\Geo\City;
use App\Models\Plan\PlanPackage;
use App\Models\Schedule\Schedule;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Jamaah extends Model
{
    use HasFactory;

    protected $table = 'jamaah';


    /**
     * Get the user that owns the Jamaah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The roles that belong to the Jamaah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function planPackages(): BelongsToMany
    {
        return $this->morphToMany(PlanPackage::class, 'model', 'model_has_package', 'model_id')->latest();
    }

    public function tabunganPackages(): MorphMany
    {
        return $this->morphMany(VirtualAccount::class, 'vaable', 'model_type', 'model_id');
    }


    /**
     * Get the user that owns the Jamaah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departureCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'departure_city_id');
    }

    /**
     * Get the user that owns the Jamaah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departureSchedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

}
