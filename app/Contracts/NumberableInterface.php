<?php

namespace App\Contracts;

use App\Models\GenerateNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @author      dsw <dswtech@gmail.com>
 *
 * @property Model<GenerateNumber> $numbers
 * @property string $getNumberableName
 * @property string|integer $getNumberableId
 */
interface NumberableInterface
{
    /**
     * Define morphToMany relationship with GenerateNumber model.
     */
    public function numbers(): MorphMany;

    /**
     * Get numberable name mapping.
     */
    public function getNumberableName(): string;

    /**
     * Get numberable id.
     */
    public function getNumberableId(): int|string;
}
