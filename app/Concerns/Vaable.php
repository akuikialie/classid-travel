<?php

namespace App\Concerns;

use App\Models\GenerateNumber;
use App\Models\VA\VirtualAccount;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @author      winatabayu <winatabayu@gmail.com>
 */
trait Vaable
{
    /**
     * @return MorphOne
     */
    public function tabungan(): MorphOne
    {
        return $this->morphOne(VirtualAccount::class, 'vaable', 'model_type', 'model_id');
    }

    /**
     * Get vaable id.
     */
    public function getVaableId(): int|string
    {
        return $this->{$this->getKeyName()};
    }
}
