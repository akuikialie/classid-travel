<?php

namespace App\Services;

use App\Concerns\ValidationInput;
use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Enums\ResponseCode;
use App\Exceptions\CidException;
use App\Models\Invoication\Invocation;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    use ValidationInput;

    /**
     * @param User $user
     * @param array $inputs
     * @return array
     * @throws CidException
     * @throws ValidationException
     */
    public function inquiry(User $user, array $inputs): array
    {
        $validated = $this->validate($inputs, [
            "virtual_account" => ['required', 'string'],
            "channel" => ['required', 'string'],
            "prefix" => ['required', 'string'],
            "reference_id" => ['required', 'string'],
            "bill_mode" => ['required', 'string'],
            "terminal" => ['required', 'string'],
        ]);

        DB::beginTransaction();
        $account = VirtualAccount::query()
            ->where('va_number', $inputs['virtual_account'])
            ->first();
        if ($account instanceof VirtualAccount) {
            throw new CidException(rc: ResponseCode::BILLER_INVALID_VA_NUMBER);
        }
        $invoiceNumber = generateInvoiceNumber($account->tenant, true)[0];
        $type = 'open';

        // check reference id and valid until on invoice
        $invocation = Invocation::query()
            ->where('reference_id', '=', $validated['reference_id'])
            ->whereBetween('valid_until', [now()->startOfDay()->toIso8601String(), now()->endOfDay()->toIso8601String()])
            ->first();


        if ($invocation instanceof Invocation) {
            throw new CidException(rc: ResponseCode::BILLER_INVALID_TRANSACTION);
        }

        // save to invocation
        $description = $account->name;
        $validUntil = now()->addDays(1)->toIso8601String();
        $invocation = new Invocation();
        $invocation->fill([
            'invoice_number' => $invoiceNumber,
            'virtual_account' => $account->va_number,
            'reference_id' => $validated['reference_id'],
            'tenant_id' => $account->tenant_id,
            'description' => $description,
            'type' => $type,
            'valid_until' => $validUntil,
        ]);
        $invocation->save();

        // save outbound
        $data = [
            "hash" => Str::random(8),
            "va_number" => $account->va_number,
            "type" => $type,
            "invoice_number" => $invoiceNumber,
            "name" => "SPP Bulan Januari",
            "customer_name" => $user->name,
            "customer_email" => $user->email,
            "customer_phone" => $user->phone,
            "customer_address" => "Surabaya, Indonesia",
            "total_amount" => 0,
            "billed_amount" => 0,
            "paid_amount" => 0,
            "description" => $description,
            "ext_description" => "-",
            "valid_until" => $validUntil,
            "status" => "active",
            "created_at" => now()->toIso8601String(),
            "components" => [
                [
                    "id" => $invocation->id,
                    "name" => $description,
                    "qty" => "1",
                    "price" => "0",
                    "total" => "0"
                ]
            ],
            "additional_data" => [
                [
                    "key" => $description,
                    "label" => $description,
                    "value" => ""
                ]
            ]
        ];

        DB::commit();

        return $data;
    }

    /**
     * @param User $user
     * @param array $inputs
     * @return array
     * @throws ValidationException
     */
    public function payment(User $user, array $inputs): array
    {
        $validated = $this->validate($inputs, [
            "virtual_account" => ['required', 'string'],
            "channel" => ['required', 'string'],
            "prefix" => ['required', 'string'],
            "reference_id" => ['required', 'string'],
            "payment_ref" => ['required', 'string'],
            "amount" => ['required', 'string'],
            "bill" => ['nullable', 'array'],
            "terminal" => ['required', 'string'],
        ]);

        DB::beginTransaction();
        $invocation = Invocation::query()
            ->where('va_number', '=', $validated['virtual_account'])
            ->whereBetween('valid_until', [now()->startOfDay()->toIso8601String(), now()->endOfDay()->toIso8601String()])
            ->first();

        // virtual account
        $virtualAccount = VirtualAccount::query()
            ->where('va_number', '=', $validated['virtual_account'])
            ->firstOrFail();
        $virtualAccount->amount = $validated['amount'];
        $virtualAccount->save();

        // save to transaction
        $transaction = new Transaction();
        $transaction->fill([
            'tenant_id' => $invocation->tenant_id,
            'invocation_id' => $invocation->id,
            'amount' => $validated['amount'],
            'trx_method' => TransactionMethod::BANK->value,
            'trx_type' => TransactionType::PAYMENT->value,
            'trx_date' => now()->toIso8601String(),
        ]);
        $transaction->save();

        // change invoice active
        $data = [
            "hash" => Str::random(8),
            "va_number" => $invocation->virtual_account,
            "invoice_number" => $invocation->invoice_number,
            "type" => $invocation->type,
            "transaction_ref_id" => $validated['reference_id'],
            "name" => $invocation->description,
            "customer_name" => $user->name,
            "customer_email" => $user->email,
            "customer_phone" => $user->phone,
            "customer_address" => "Surabaya, Indonesia",
            "total_amount" => $validated['amount'],
            "billed_amount" => $validated['amount'],
            "paid_amount" => $validated['amount'],
            "status" => "active",
            "description" => $invocation->description,
            "ext_description" => "-",
            "valid_until" => $invocation->valid_until,
            "created_at" => now()->toIso8601String(),
            "components" => [
                [
                    "id" => $invocation->id,
                    "name" => $invocation->description,
                    "qty" => "1",
                    "price" => $validated['amount'],
                    "total" => $validated['amount']
                ]
            ],
            "additional_data" => [
                [
                    "key" => $invocation->description,
                    "label" => $invocation->description,
                    "value" => ""
                ]
            ]
        ];

        DB::commit();

        return $data;
    }
}
