<?php

namespace App\Models\Plan;

use App\Models\Master\Define;
use App\Traits\ModelDefines;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PlanFacility extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'facilities';
    protected $fillable = [
        'name', 'type',
    ];

    const TypePerjalanan = 'Perjalanan';
    const TypePenginapan = 'Penginapan';
    const TypeMakan = 'Makan';

    // SCOPES

    // ACCESSOR & MUTATOR

    // RELATIONSHIPS

     /**
     * The roles that belong to the PlanPackage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->morphedByMany(PlanPackage::class, 'model', 'model_has_facility', 'plan_facility_id', 'model_id');
    }


}
