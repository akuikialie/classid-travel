<?php

namespace App\Concerns;

use App\Models\GenerateNumber;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @author      dsw <dswtech@gmail.com>
 */
trait Numberable
{
    /**
     * Define morphToMany relationship with GenerateNumber model.
     */
    public function numbers(): MorphMany
    {
        return $this->morphMany(GenerateNumber::class, 'numberable', 'numberable_type', 'numberable_id');
    }

    /**
     * Get numberable id.
     */
    public function getNumberableId(): int|string
    {
        return $this->{$this->getKeyName()};
    }
}
