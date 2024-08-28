<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GeoSeeder::class,
            TenantSeeder::class,

            RbacSeeder::class,
            UserSeeder::class,

            PlanSeeder::class,
            FacilitySeeder::class,
            DestinationSeeder::class,
            PackageSeeder::class,
        ]);
    }
}
