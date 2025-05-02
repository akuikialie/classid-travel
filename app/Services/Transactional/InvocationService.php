<?php

namespace App\Services\Transactional;

use App\Enums\InvocationStatus;
use App\Enums\InvocationType;
use App\Models\Invoication\Invocation;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseService;
use Illuminate\Validation\ValidationException;

class InvocationService extends BaseService
{
    /**
     * @param User $user
     * @param VirtualAccount $account
     * @param string $invoiceNumber
     * @param InvocationType $type
     * @param InvocationStatus $status
     * @param array $inputs
     * @return Invocation
     * @throws ValidationException
     */
    public function create(User $user, VirtualAccount $account, string $invoiceNumber, InvocationType $type, InvocationStatus $status, array $inputs): Invocation
    {
        $validated = $this->validate(
            inputs: $inputs,
            rules: [
                'reference_id' => ['required', 'string'],
                'valid_until' => ['required', 'date'],
                'description' => ['nullable', 'string'],
            ]
        );
        $invocation = new Invocation();
        $invocation->fill([
            'user_id' => $user->id,
            'invoice_number' => $invoiceNumber,
            'virtual_account' => $account->va_number,
            'reference_id' => $validated['reference_id'],
            'tenant_id' => $account->tenant_id,
            'description' => $validated['description'],
            'type' => $type->value,
            'valid_until' => $validated['valid_until'],
        ]);
        $invocation->status = $status->value;
        $invocation->save();

        return $invocation;
    }
}
