<?php

namespace Database\Seeders;

use App\Enums\FacilityType;
use App\Models\Plan\PlanFacility;
use Illuminate\Database\Seeder;

class SeedFacilities extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $facilities = [
            [
                'name' => 'Nasi kotak',
                'type' => FacilityType::makanan->keyValue(),
            ], [
                'name' => 'Hotel Hidayah',
                'type' => FacilityType::penginapan->keyValue(),
            ], [
                'name' => 'Lion Air',
                'type' => FacilityType::perjalanan->keyValue(),
            ],
        ];

        foreach ($facilities as $facility) {
            PlanFacility::query()->create(
                array_merge(['tenant_id' => rand(1, 2)],
                    $facility)
            );
        }
    }
}
