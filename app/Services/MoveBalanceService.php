<?php

namespace App\Services;

use App\Concerns\InteractsWithMutation;
use App\Enums\InvocationStatus;
use App\Enums\InvocationType;
use App\Enums\MutationInfo;
use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Exceptions\CidException;
use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use App\Services\Transactional\InvocationService;
use App\Services\Transactional\TransactionService;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MoveBalanceService extends BaseService
{

    use InteractsWithMutation;


    /**
     * @param User $actor
     * @param array $inputs
     * @return void
     * @throws ValidationException
     */
    public function moveBalance(User $actor, array $inputs): void
    {
        $validated = $this->validate(
            inputs: $inputs,
            rules: [
                'user_id' => ['required', 'string'],
                'source_balance' => ['required', 'string'],
                'destination_balance' => ['required', 'string'],
                'amount' => ['required', 'numeric', 'gte:0'],
            ]
        );

        /**
         * @var User $user
         * @var VirtualAccount $sourceBalance
         * @var VirtualAccount $destinationBalance
         */
        $user = User::byHash($validated['user_id']);
        if (!$user instanceof User) {
            throw new ModelNotFoundException('User Tidak Ditemukan');
        }
        $sourceBalance = VirtualAccount::byHash($validated['source_balance']);
        if (!$sourceBalance instanceof VirtualAccount) {
            throw new ModelNotFoundException('Sumber Virtual Akun Tidak Ditemukan');
        }
        $this->validityOwner(user: $user, account: $sourceBalance);

        $destinationBalance = VirtualAccount::byHash($validated['destination_balance']);
        if (!$destinationBalance instanceof VirtualAccount) {
            throw new ModelNotFoundException('Tujuan Virtual Akun Tidak Ditemukan');
        }
        $this->validityOwner(user: $user, account: $destinationBalance);

        if ($sourceBalance->id == $destinationBalance->id) {
            throw new \Exception('Tidak bisa memindahkan saldo ke tabungan yang sama');
        }

        DB::transaction(function () use ($actor, $inputs, $validated, $sourceBalance, $destinationBalance, $user) {
            // create invocation
            $generateNumber = generateInvoiceNumberMoveBalance(
                tenant: $user->tenant,
                transactionType: TransactionType::MOVE,
                isLocking: true
            )[0];
            $invocation = (new InvocationService())->create(
                user: $user,
                account: $sourceBalance,
                invoiceNumber: $generateNumber,
                type: InvocationType::MOVE_ACCOUNT_BALANCE,
                status: InvocationStatus::PAID,
                inputs: [
                    'reference_id' => (string)now()->unix(),
                    'valid_until' => now()->addDay()->toDateTimeString(),
                    'description' => __("Move balance from {$sourceBalance->va_label} to {$destinationBalance->va_label} by admin"),
                ]
            );

            // create transaction
            $number = generateInvoiceNumberMoveBalance(tenant: $user->tenant, transactionType: TransactionType::MOVE, isLocking: true)[0];
            $amount = $validated['amount'];
            $transaction = (new TransactionService())->create(
                user: $user,
                invocation: $invocation,
                number: $number,
                trxType: TransactionType::MOVE,
                trxMethod: TransactionMethod::SYSTEM,
                inputs: [
                    'amount' => $validated['amount'],
                ]
            );

            // create mutation
            $mutation = $this->init(tenant: $user->tenant, transaction: $transaction);

            $mutation
                ->setMutable(amount: $amount * -1, mutable: $sourceBalance, mutationInfo: MutationInfo::MOVE)
                ->setMutable(amount: $amount, mutable: $destinationBalance, mutationInfo: MutationInfo::MOVE);
        });

    }

    /**
     * @param User $user
     * @param VirtualAccount $account
     * @return void
     * @throws \Exception
     */
    private function validityOwner(User $user, VirtualAccount $account): void
    {
        $ownerVirtualAccount = $account->vaable;

        if ($ownerVirtualAccount instanceof Jamaah) {
            if ($ownerVirtualAccount->id != $user->jamaah->id) {
                throw new \Exception('Invalid Owner Virtual Account', 900);
            }
        }

        if ($ownerVirtualAccount instanceof User) {
            if ($ownerVirtualAccount->id != $user->id) {
                throw new \Exception('Invalid Owner Virtual Account', 900);
            }
        }
    }
}
