<?php

namespace App\Services\Transactional;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoication\Invocation;
use App\Models\Transaction\Transaction;
use App\Models\User;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseService;
use Illuminate\Validation\ValidationException;

class TransactionService extends BaseService
{
    /**
     * @param User $user
     * @param Invocation $invocation
     * @param string $number
     * @param TransactionType $trxType
     * @param TransactionMethod $trxMethod
     * @param array $inputs
     * @return Transaction
     * @throws ValidationException
     */
    public function create(User $user, Invocation $invocation, string $number, TransactionType $trxType, TransactionMethod $trxMethod, array $inputs): Transaction
    {
        $validated = $this->validate(
            inputs: $inputs,
            rules: [
                'amount' => ['required', 'numeric', 'gte:0'],
            ]
        );

        $transaction = new Transaction();
        $transaction->fill([
            'user_id' => $user->id,
            'tenant_id' => $invocation->tenant_id,
            'invocation_id' => $invocation->id,
            'trx_number' => $number,
            'amount' => $validated['amount'],
            'trx_method' => $trxMethod->value,
            'trx_type' => $trxType->value,
            'trx_date' => now()->toIso8601String(),
        ]);

        $transaction->save();

        return $transaction;
    }
}
