<?php

namespace App\Console\Commands;

use App\Enums\GenerateNumberType;
use App\Models\GenerateNumber\GenerateNumber;
use App\Models\Tenant\Tenant;
use Illuminate\Console\Command;

class FillMissingGenerateNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-missing-generate-number';

    /**
     * The console command description.
     *fill-missing-generate-number
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::query()
            ->get();
        foreach ($tenants as $tenant) {
            $generateNumbers = [
                GenerateNumberType::TRANSACTION_NUMBER->value => GenerateNumberType::TRANSACTION_NUMBER->uniqueGenerateNumberTemplate(),
                GenerateNumberType::INVOICE_NUMBER->value => GenerateNumberType::INVOICE_NUMBER->uniqueGenerateNumberTemplate(),
                GenerateNumberType::VIRTUAL_NUMBER->value => GenerateNumberType::VIRTUAL_NUMBER->uniqueGenerateNumberTemplate(),
                GenerateNumberType::TRANSACTIONAL_NUMBER->value => GenerateNumberType::TRANSACTIONAL_NUMBER->uniqueGenerateNumberTemplate(),
            ];
            foreach ($generateNumbers as $type => $pattern) {
                $this->createGenerateNumber($tenant, $type, $pattern);
            }
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
}
