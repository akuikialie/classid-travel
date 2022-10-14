<?php

namespace App\Models\Destination;

use App\Models\Master\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

}
