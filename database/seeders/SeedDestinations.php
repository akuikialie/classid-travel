<?php

namespace Database\Seeders;

use App\Models\Destination\Destination;
use Illuminate\Database\Seeder;

class SeedDestinations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $detinations = [
            [
                'name' => 'Makkah',
            ], [
                'name' => 'Turkey',
            ], [
                'name' => 'Dubai',
            ],
        ];

        foreach ($detinations as $detination){
            Destination::query()->create(
                array_merge(['tenant_id' => rand(1, 2)],
                    $detination)
            );
        }
    }
}
