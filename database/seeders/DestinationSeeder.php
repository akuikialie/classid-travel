<?php

namespace Database\Seeders;

use App\Models\Destination\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $detinations = ['Makkah', 'Turkey', 'Dubai'];

        foreach ($detinations as $detination){
            Destination::query()->create([
                'name' => $detination,
                'tenant_id' => rand(2, 6),
            ]);
        }
    }
}
