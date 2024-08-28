<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Address extends Model
{
    use HasFactory, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

    protected $table = 'addresses';

    protected $fillable = [
        'tenant_id',
        'name',
        'address'
    ];
}
