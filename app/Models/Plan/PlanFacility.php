<?php

namespace App\Models\Plan;

use App\Models\Master\Define;
use App\Traits\ModelDefines;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PlanFacility extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'facilities';
    protected $fillable = [
        'name', 'type',
    ];

    const TypePerjalanan = 'Perjalanan';
    const TypePenginapan = 'Penginapan';
    const TypeMakan = 'Makan';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('facility', function (Builder $builder) {
            $builder->whereType('facility')->orderBy('order');
        });
    }

    // SCOPES

    // ACCESSOR & MUTATOR

    // RELATIONSHIPS

}
