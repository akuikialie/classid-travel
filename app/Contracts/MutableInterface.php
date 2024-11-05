<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @author      dsw <dswtech@gmail.com>
 */
interface MutableInterface
{
    /**
     * Define morphToMany relationship with Mutation model.
     */
    public function mutations(): MorphMany;

    /**
     * Get starting balance.
     */
    public function getStartingBalance(): float;

    /**
     * Get balance key column.
     */
    public function getBalanceKey(): string;

    /**
     * Get paid balance. if any
     */
    public function getStartingPaidAmount(): float|null;

    /**
     * Get paid balance key column.
     */
    public function getStartingPaidAmountKey(): string|null;

    /**
     * Get mutable name mapping.
     */
    public function getMutableName(): string;

    /**
     * Get mutable id.
     */
    public function getMutableId(): int|string;

    /**
     * Reload a fresh model instance from the database.
     */
    public function freshLock(array|string $with = []): mixed;

    /**
     * Allow negative balance.
     */
    public function allowNegativeBalance(): bool;

    public function getMutableItemName(): string;
}
