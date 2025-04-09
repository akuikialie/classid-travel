<?php

namespace App\Models\Inbound;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    use HasUuids;

    protected $table = 'inbounds';

    protected $fillable = [
        'ip',
        'user_agent',
        'method',
        'url',
        'actions',
        'headers',
        'params',
        'body',
    ];

    protected $casts = [
        'headers' => 'array',
        'params' => 'array',
        'body' => 'array',
    ];
}
