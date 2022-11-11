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
                'name' => 'Travel 1',
                'slug' => 'slug1',
                'app_domain' => 'travel1.demo.prohajj.app',
                'BCN' => '857601',
            ],
            [
                'name' => 'Travel 2',
                'slug' => 'slug2',
                'app_domain' => 'travel2.demo.prohajj.app',
                'BCN' => '857602',
            ],
            [
                'name' => 'Travel 3',
                'slug' => 'slug3',
                'app_domain' => 'travel3.demo.prohajj.app',
                'BCN' => '857603',
            ],
            [
                'name' => 'Travel 4',
                'slug' => 'slug4',
                'app_domain' => 'travel4.demo.prohajj.app',
                'BCN' => '857604',
            ],
            [
                'name' => 'Travel 5',
                'slug' => 'slug5',
                'app_domain' => 'travel5.demo.prohajj.app',
                'BCN' => '857605',
            ],

            /* tenant dev */
            [
                'name' => 'Tenant Development',
                'slug' => 'for development',
                'app_domain' => 'd1.'.env('ADMIN_URL'),
                'BCN' => '000000',
            ],
        ];

        foreach ($seedTenants as $tenant){
            Tenant::query()->create($tenant);
        }

    }
}
