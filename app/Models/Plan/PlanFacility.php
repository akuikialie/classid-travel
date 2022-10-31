<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PlanFacility extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const TypePerjalanan = 'Perjalanan';
    const TypePenginapan = 'Penginapan';
    const TypeMakan = 'Makan';



    protected $table = 'facilities';
    protected $fillable = [
        'tenant_id', 'name', 'type',
    ];

    // SCOPES

    // ACCESSOR & MUTATOR

    // RELATIONSHIPS

    /**
     * The roles that belong to the PlanPackage
     *
     * @return BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->morphedByMany(PlanPackage::class, 'model', 'model_has_facility', 'plan_facility_id', 'model_id');
    }


}
