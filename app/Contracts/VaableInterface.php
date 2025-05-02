<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @author      winatabayu <winatabayu@gmail.com>
 */
interface VaableInterface
{
    /**
     * Define morphToMany relationship with GenerateNumber model.
     */
    public function vaable(): MorphOne;

    /**
     * Get vaable name mapping.
     */
    public function getVaableName(): string;

    /**
     * Get vaable id.
     */
    public function getVaableId(): int|string;
}
