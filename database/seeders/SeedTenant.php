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
                'slug' => 'slug1',
                'app_domain' => 'd1.umrah.haji.test',
                'BCN' => '857601',
            ],
            [
                'name' => 'Malaysia',
                'slug' => 'slug2',
                'app_domain' => 'd2.umrah.haji.test',
                'BCN' => '857602',
            ],
        ];

        foreach ($seedTenants as $tenant){
            Tenant::query()->create($tenant);
        }

    }
}
