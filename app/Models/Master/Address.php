<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    function addressable()
    {
        $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
