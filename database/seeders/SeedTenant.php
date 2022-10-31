<?php

namespace Database\Seeders;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Seeder;

class SeedTenant extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedTenants = [
            [
                'name' => 'Indonesia',
                'slug' => 'Tenant slug',
                'app_domain' => 'https://prohajj.app',
                'BCN' => '857601',
            ],
            [
                'name' => 'Malaysia',
                'slug' => 'Tenant slug',
                'app_domain' => 'https://prohajj.app',
                'BCN' => '857602',
            ],
        ];

        foreach ($seedTenants as $tenant){
            Tenant::query()->create($tenant);
        }

    }
}
