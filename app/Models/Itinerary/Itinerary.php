<?php

namespace App\Models\Itinerary;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Itinerary extends Model
{
    use HasFactory, HasTenant, HashableId, SoftDeletes;

    protected bool $shouldHashPersist = true;

    protected $table = 'itineraries';

    protected $fillable = [
        'tenant_id',
        'name',
        'day',
    ];

    /**
     * The roles that belong to the PlanPackage
     *
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->morphToMany(ItineraryActivity::class,'model',
            'model_has_itinerary_activity', 'model_id')
            ->withPivot('time')
            ->oldest('time');
    }
}
