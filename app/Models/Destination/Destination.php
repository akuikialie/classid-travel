<?php

namespace App\Models\Destination;

use App\Models\Master\Address;
use App\Models\Plan\PlanPackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Destination extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'destinations';

    protected $fillable = ['name', 'roaming_in_destination'];

    public function myAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable', 'model_type', 'model_id')->latestOfMany();
    }

    /**
     * The roles that belong to the PlanPackage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->morphedByMany(PlanPackage::class, 'model', 'model_has_destination', 'destination_id', 'model_id');
    }

}
