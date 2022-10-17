<?php

namespace App\Models\Plan;

use App\Models\Destination\Destination;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PlanPackage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'plan_packages';

    protected $fillable = ['name', 'description', 'amount', 'departure_year', 'kuartal', 'long_days'];

    /**
     * Get the user that owns the PlanPackage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function myPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }


    /**
     * The roles that belong to the PlanPackage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function myFacilities(): BelongsToMany
    {
        return $this->morphToMany(PlanFacility::class, 'model', 'model_has_facility', 'model_id')->latest();
    }

    /**
     * The roles that belong to the PlanPackage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function myDestinations(): BelongsToMany
    {
        return $this->morphToMany(Destination::class,'model', 'model_has_destination', 'model_id')->latest();
    }

}
