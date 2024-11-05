<?php

namespace App\Services\GenerateNumber;

use App\Models\GenerateNumber\GenerateNumber;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\DB;

class GenerateNumberService
{
    /**
     * Warning: Please aware :)
     * This service is flexible level.
     * If you send the isLocking parameter is TRUE.
     * You must add DB Transaction to the class that calls this service
     *
     * @param Tenant $tenant
     * @param string $type
     * @param bool $isTransactional
     * @param int $numberGenerated
     *
     * @return GenerateNumber
     */

    public function getNextGenerateNumber(
        Tenant $tenant,
        string $type,
        bool $isTransactional = true,
        int $numberGenerated = 1
    ): GenerateNumber
    {
        if ($isTransactional) {
            DB::beginTransaction();
        }

        $prefixNumber =
            substr(date('Y'), -3) .
            date('m');

        /* @var GenerateNumber $generateNumber */
        $generateNumber = GenerateNumber::query()
            ->where([
                'tenant_id' => $tenant->id,
                'type' => $type,
            ])
            ->lockForUpdate()
            ->firstOrFail();

        // Get Current Number
        $currentNumber = $generateNumber->current_number;
        $newNumber = $currentNumber + $numberGenerated;

        $generatedNumbers = range(start: $currentNumber+1, end: $newNumber);

        // Increment Current Number
        $generateNumber->current_number = $newNumber;
        $generateNumber->save();

        $generateNumber->fresh();

        if ($isTransactional) {
            DB::commit();
        }
        $generateNumber->generated_numbers = $generatedNumbers;

        return $generateNumber;
    }

    /**
     * @param Tenant $tenant
     * @param string $numberableType
     * @param string|null $pattern
     * @return GenerateNumber
     */
    public function create(
        Tenant $tenant,
        string $numberableType,
        string $pattern=null,
    ): GenerateNumber
    {
        $data = [
            'type' => $numberableType,
            'number_pattern' => $pattern,
        ];

        $newNumberable = new GenerateNumber($data);
        $newNumberable->tenant()->associate($tenant);

        $newNumberable->save();

        return $newNumberable;
    }
}
