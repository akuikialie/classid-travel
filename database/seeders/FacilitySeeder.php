<?php

namespace Database\Seeders;

use App\Enums\FacilityType;
use App\Models\Plan\PlanFacility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Nasi kotak',
                'type' => FacilityType::makanan->keyValue(),
            ],
            [
                'name' => 'Hotel Hidayah',
                'type' => FacilityType::penginapan->keyValue(),
            ],
            [
                'name' => 'Lion Air',
                'type' => FacilityType::perjalanan->keyValue(),
            ],
        ];

        foreach ($facilities as $facility) {
            $facility['tenant_id'] = rand(2, 6);
            PlanFacility::query()->create($facility);
        }
    }
}
