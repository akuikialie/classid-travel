<?php

use App\Enums\GenerateNumberType;
use App\Models\GenerateNumber\GenerateNumber;
use Dentro\Patcher\Patch;

return new class extends Patch {

    public function __construct()
    {
        $this->isPerpetual = isNonProduction();
    }

    /**
     * @return true
     */
    public function eligible()
    {
        return true;
    }

    /**
     * Run patch script.
     *
     * @return void
     */
    public function patch(): void
    {
        // create generate number
        $tenants = \App\Models\Tenant\Tenant::query()
            ->get();

        foreach ($tenants as $tenant) {
            $this->generateNumber($tenant);
            $this->updateTransaction($tenant);
        }
    }

    /**
     * @param \App\Models\Tenant\Tenant $tenant
     * @return void
     */
    private function updateTransaction(\App\Models\Tenant\Tenant $tenant): void
    {
        $transactions = \App\Models\Transaction\Transaction::query()
            ->where('tenant_id', $tenant->id)
            ->orderBy('trx_date', 'asc')
            ->get();

        /** @var \App\Models\Transaction\Transaction $transaction */
        foreach ($transactions as $transaction) {
            $transaction->trx_type = \App\Enums\TransactionType::DEPOSIT->value;
            $trxType = \App\Enums\TransactionType::DEPOSIT;
            $number = generateTransactionNumber(tenant: $tenant, transactionType: $trxType, isLocking: true)[0];
            $transaction->trx_number = $number;
            $transaction->save();
        }
    }

    /**
     * Execute the job.
     */
    public function generateNumber(\App\Models\Tenant\Tenant $tenant): void
    {
        $generateNumbers = [
            GenerateNumberType::TRANSACTION_NUMBER->value => '{trx_type}{month_year}#########',
            GenerateNumberType::INVOICE_NUMBER->value => 'INV-{month_year}#########',
            GenerateNumberType::VIRTUAL_NUMBER->value => '{tenant_bcn}{month_year}#####',
        ];
        foreach ($generateNumbers as $type => $pattern) {
            $this->createGenerateNumber($tenant, $type, $pattern);
        }
    }

    /**
     * @param \App\Models\Tenant\Tenant $tenant
     * @param string $numberType
     * @param string $pattern
     * @return void
     */
    private function createGenerateNumber(\App\Models\Tenant\Tenant $tenant, string $numberType, string $pattern): void
    {
        $type = GenerateNumberType::tryFrom($numberType);
        $numberableExists = GenerateNumber::query()
            ->where([
                'tenant_id' => $tenant->id,
                'type' => $type->value
            ])
            ->first();

        if (is_null($numberableExists)) {
            GenerateNumber::query()
                ->create([
                    'tenant_id' => $tenant->id,
                    'type' => $type->value,
                    'number_pattern' => $pattern,
                    'should_reset' => 'monthly',
                ]);
        }
    }
};

