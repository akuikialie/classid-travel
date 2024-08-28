<?php

namespace App\Models\Itinerary;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class ItineraryActivity extends Model
{
    use HasFactory, HashableId, HasTenant, SoftDeletes;

    protected bool $shouldHashPersist = true;

    protected $table = 'itinerary_activities';

    protected $fillable = [
        'tenant_id',
        'itinerary_id',
        'activity',
        'detail',
    ];

    /**
     * The roles that belong to the PlanPackage
     *
     * @return BelongsToMany
     */
    public function hasItineraries(): BelongsToMany
    {
        return $this->morphedByMany(Itinerary::class, 'model', 'model_has_itinerary_activity', 'itinerary_activity_id', 'model_id');
    }
}
