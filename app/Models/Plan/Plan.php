<?php

namespace App\Models\Plan;

use App\Models\Master\Define;
use App\Traits\ModelDefines;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Define
{
    use HasFactory, ModelDefines, SoftDeletes;

     /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('plan', function (Builder $builder) {
            $builder->whereType('plan')->orderBy('order');
        });
    }

    // SCOPES

    // ACCESSOR & MUTATOR

    // RELATIONSHIPS

}
