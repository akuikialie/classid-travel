<?php

use Dentro\Patcher\Patch;

return new class extends Patch
{

    public function __construct()
    {
        $this->isPerpetual = isNonProduction();
    }

    public function eligible()
    {
        return false;
    }

    /**
     * Run patch script.
     *
     * @return void
     */
    public function patch(): void
    {
        $invocations = \App\Models\Invoication\Invocation::query()
            ->with(['transaction'])
            ->where('status', '=', \App\Enums\InvocationStatus::PAID->value)
            ->get();

        $vaNumbers = $invocations->pluck('virtual_account')->toArray();
        $virtualAccounts = \App\Models\VA\VirtualAccount::query()
            ->whereIn('va_number', $vaNumbers)
            ->get();

        foreach ($invocations as $invocation) {
            $virtualAccount = $virtualAccounts->where('va_number', '=', $invocation->virtual_account)->first();
            if (!empty($virtualAccount)) {
                $getUserId = $virtualAccount->model_id;

                //update user id on invocation
                /** @var \App\Models\Invoication\Invocation $invocation */
                $invocation->user_id = $getUserId;
                //update user id on transaction
                $invocation->transaction->user_id = $getUserId;
                $invocation->push();
            }
        }
    }
};
