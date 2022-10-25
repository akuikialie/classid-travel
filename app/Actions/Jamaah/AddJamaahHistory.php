<?php

namespace App\Actions\Jamaah;

use App\Models\Jamaah\Jamaah;
use App\Models\Jamaah\JamaahHistory;
use App\Models\Plan\PlanPackage;
use Throwable;

class AddJamaahHistory
{
    public function handle(Jamaah $jamaah, ?string $detail = null): void
    {
        try {
            $newDepartureHistory = new JamaahHistory([
                'detail' => $detail,
            ]);

            $jamaah->departureHistory()->save($newDepartureHistory);
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
