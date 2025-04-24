<?php

namespace App\Jobs;

use App\Enums\GenerateNumberType;
use App\Models\GenerateNumber\GenerateNumber;
use App\Models\Tenant\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateDefaultRecordForCreatedTenant implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Tenant $tenant,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $generateNumbers = [
            GenerateNumberType::TRANSACTION_NUMBER->value => '{trx_type}{month_year}#########',
            GenerateNumberType::INVOICE_NUMBER->value => 'INV-{month_year}#########',
            GenerateNumberType::VIRTUAL_NUMBER->value => '{tenant_bcn}{month_year}#####',
        ];
        foreach ($generateNumbers as $type => $pattern) {
            $this->createGenerateNumber($type, $pattern);
        }
    }

    private function createGenerateNumber(string $numberType, string $pattern): void
    {
        $type = GenerateNumberType::tryFrom($numberType);
        $numberableExists = GenerateNumber::query()
            ->where([
                'tenant_id' => $this->tenant->id,
                'type' => $type->value
            ])
            ->first();

        if (is_null($numberableExists)) {
            GenerateNumber::query()
                ->create([
                    'tenant_id' => $this->tenant->id,
                    'type' => $type->value,
                    'number_pattern' => $pattern,
                    'should_reset' => 'monthly',
                ]);
        }
    }
}
