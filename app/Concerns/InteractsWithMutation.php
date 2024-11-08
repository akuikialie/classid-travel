<?php

namespace App\Concerns;

use App\Contracts\MutableInterface;
use App\Services\Mutation\MutationService;
use App\Enums\MutationInfo;
use App\Exceptions\CidException;
use App\Models\Mutation\Mutation;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Transaction;
use App\Models\User;

trait InteractsWithMutation
{
    protected Tenant $tenant;
    protected Transaction $transaction;

    /**
     * @param Tenant $tenant
     * @param Transaction $transaction
     * @return $this
     */
    public function init(Tenant $tenant, Transaction $transaction): static
    {
        $this->tenant = $tenant;
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @param float $amount
     * @param MutableInterface $mutable
     * @param MutationInfo $mutationInfo
     * @param bool $isLocking
     * @return $this
     * @throws CidException
     */
    public function setMutable(
        float            $amount,
        MutableInterface $mutable,
        MutationInfo     $mutationInfo,
        bool             $isLocking = true
    ): static
    {
        $this->interactWithMutation(
            amount: $amount,
            mutable: $mutable,
            tenant: $this->tenant,
            transaction: $this->transaction,
            mutationInfo: $mutationInfo,
            isLocking: $isLocking
        );

        return $this;
    }

    /**
     * @param float $amount
     * @param MutableInterface $mutable
     * @param Tenant $tenant
     * @param Transaction $transaction
     * @param MutationInfo $mutationInfo
     * @param bool $isLocking
     * @return Mutation
     * @throws CidException
     */
    protected function interactWithMutation(
        float            $amount,
        MutableInterface $mutable,
        Tenant           $tenant,
        Transaction      $transaction,
        MutationInfo     $mutationInfo,
        bool             $isLocking = true
    ): Mutation
    {
        return (new MutationService())
            ->create(
                mutable: $mutable,
                amount: $amount,
                transaction: $transaction,
                tenant: $tenant,
                mutationInfo: $mutationInfo,
                isLocking: $isLocking
            );
    }
}
