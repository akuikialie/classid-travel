<?php

namespace App\Models\Plan;

use App\Models\Destination\Destination;
use App\Models\HashableId;
use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PlanPackage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HashableId, HasTenant, SoftDeletes;

    protected $table = 'plan_packages';

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'name',
        'description',
        'amount',
        'departure_year',
        'kuartal',
        'long_days'
    ];

    /**
     * Get the user that owns the PlanPackage
     *
     * @return BelongsTo
     */
    public function myPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }


    /**
     * The roles that belong to the PlanPackage
     *
     * @return BelongsToMany
     */
    public function myFacilities(): BelongsToMany
    {
        return $this->morphToMany(PlanFacility::class, 'model', 'model_has_facility', 'model_id')->latest();
    }

    /**
     * The roles that belong to the PlanPackage
     *
     * @return BelongsToMany
     */
    public function myDestinations(): BelongsToMany
    {
        return $this->morphToMany(Destination::class,'model', 'model_has_destination', 'model_id')->latest();
    }

    /**
     * The roles that belong to the PlanPackage
     *
     * @return BelongsToMany
     */
    public function jamaah(): BelongsToMany
    {
        return $this->morphedByMany(Jamaah::class, 'model', 'model_has_package', 'plan_package_id', 'model_id');
    }

    // public function users(): BelongsToMany
    // {
    //     return $this->morphedByMany(
    //         getModelForGuard($this->attributes['guard_name']),
    //         'model',
    //         config('permission.table_names.model_has_roles'),
    //         PermissionRegistrar::$pivotRole,
    //         config('permission.column_names.model_morph_key')
    //     );
    // }

}
