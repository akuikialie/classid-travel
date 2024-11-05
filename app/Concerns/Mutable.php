<?php

namespace App\Concerns;

use App\Domains\Finance\Models\Mutation\Mutation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @author      azman-aziz <am9114444@gmail.com>
 * @property string $name
 */
trait Mutable
{
    /**
     * Get starting balance.
     */
    public function getStartingBalance(): float
    {
        return $this->attributes[$this->getBalanceKey()];
    }

    /**
     * Get Balance Key.
     */
    public function getBalanceKey(): string
    {
        return property_exists($this, 'balanceKey') ? $this->balanceKey : 'balance';
    }

    /**
     * Get starting balance.
     */
    public function getStartingPaidAmount(): float|null
    {
        return $this->attributes[$this->getStartingPaidAmountKey()];
    }

    /**
     * Get Balance Key.
     */
    public function getStartingPaidAmountKey(): string|null
    {
        return property_exists($this, 'paidAmountKey') ? $this->paidAmountKey : null;
    }

    /**
     * Define morphToMany relationship with Mutation model.
     */
    public function mutations(): MorphMany
    {
        return $this->morphMany(Mutation::class, 'mutable', 'mutable_type', 'mutable_id');
    }

    /**
     * Get mutable id.
     */
    public function getMutableId(): int|string
    {
        return $this->{$this->getKeyName()};
    }

    public function getMutableItemName(): string
    {
        return method_exists($this, 'getEntityName') ? $this->getEntityName() : $this->name;
    }
}
