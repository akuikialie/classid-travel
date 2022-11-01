<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';

    protected $fillable = [
        'tenant_id',
        'name',
        'address'
    ];

    function addressable()
    {
        $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
