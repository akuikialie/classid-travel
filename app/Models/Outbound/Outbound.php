<?php

namespace App\Models\Outbound;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    use HasUuids;

    protected $table = 'inbounds';

    protected $fillable = [
        'ip',
        'user_agent',
        'method',
        'url',
        'actions',
        'url',
        'headers',
        'params',
        'body',
    ];
}
