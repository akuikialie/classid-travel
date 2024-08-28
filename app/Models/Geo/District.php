<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class District extends Model
{
    use HasFactory, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

    protected $table = 'geo_districts';

}
