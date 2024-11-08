<?php

namespace App\Services\Mutation;

use App\Contracts\MutableInterface;
use App\Enums\MutationInfo;
use App\Enums\MutationType;
use App\Enums\ResponseCode;
use App\Exceptions\CidException;
use App\Models\Mutation\Mutation;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Model;

class MutationService
{
    /**
     * @throws CidException
     */
    public function create(
        Model|MutableInterface $mutable,
        float                  $amount,
        Transaction            $transaction,
        Tenant                 $tenant,
        MutationInfo           $mutationInfo,
        bool                   $isLocking = true,
    ): Mutation
    {
        $mutation = new Mutation();

        $mutableModel = ($isLocking) ? $mutable->freshLock() : $mutable;

        /**
         * $mutable Locking should set to false And lock should handle outside the method if using sequence mutable
         * to make sure data is integrated.
         *
         * @var Model|MutableInterface $mutable
         */

        $balance = $mutableModel->getStartingBalance();

        if (!$mutableModel->allowNegativeBalance() && ($balance < (-1 * $amount))) {
            throw new CidException(rc: ResponseCode::ERR_INSUFFICIENT_BALANCE, data: ['amount' => $amount, 'balance' => $balance]);
        }

        $mutation->mutable()->associate($mutableModel);
        $mutation->transaction_id = $transaction->id;
        $mutation->type = $amount < 0 ? MutationType::OUT->value : MutationType::IN->value;
        $mutation->info = $mutationInfo->value;
        $mutation->amount = $amount;
        $mutation->amount_before = $balance;
        $mutation->tenant_id = $tenant->id;

        // update balance
        $mutation->amount_after = $balance + $amount;

        $mutableModel->{$mutableModel->getBalanceKey()} = $balance + $amount;

        if ($mutableModel->getStartingPaidAmountKey()) {
            $mutableModel->{$mutableModel->getStartingPaidAmountKey()} = $mutableModel->getStartingPaidAmount() - $amount;
        }

        $mutation->save();
        $mutableModel->save();

        return $mutation;
    }
}
