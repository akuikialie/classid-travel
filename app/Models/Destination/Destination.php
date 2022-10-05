<?php

namespace App\Models\Destination;

use App\Models\Master\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'destinations';

    protected $fillable = ['name', 'roaming_in_destination'];

    public function myAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable', 'model_type', 'model_id');
    }

    /* colletion destination photos */
    // public function myPhotos(): MorphMany
    // {
    //     return $this->morphMany();
    // }
}
