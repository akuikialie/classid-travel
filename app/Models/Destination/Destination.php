<?php

namespace App\Models\Destination;

use App\Models\Master\Address;
use App\Models\Plan\PlanPackage;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Destination extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTenant, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

    protected $table = 'destinations';

    protected $fillable = ['tenant_id','name'];


    public function myAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable', 'model_type', 'model_id')->latestOfMany();
    }

    /**
     * The roles that belong to the PlanPackage
     *
     * @return BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->morphedByMany(PlanPackage::class, 'model', 'model_has_destination', 'destination_id', 'model_id');
    }

}
