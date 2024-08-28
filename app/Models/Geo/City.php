<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class City extends Model
{
    use HasFactory, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

    protected $table = 'geo_cities';

    protected $fillable = ['name'];
}
