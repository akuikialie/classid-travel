<?php

namespace Database\Seeders;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $seedTenants = [
            // dev
            [
                'name' => 'Tenant Development',
                'slug' => 'for-development',
                'app_domain' => 'd1.'.env('ADMIN_URL'),
                'bcn' => '000000',
            ],

            // sample
            [
                'name' => 'Travel 1',
                'slug' => 'slug1',
                'app_domain' => 'travel1.demo.prohajj.app',
                'bcn' => '857601',
            ],
            [
                'name' => 'Travel 2',
                'slug' => 'slug2',
                'app_domain' => 'travel2.demo.prohajj.app',
                'bcn' => '857602',
            ],
            [
                'name' => 'Travel 3',
                'slug' => 'slug3',
                'app_domain' => 'travel3.demo.prohajj.app',
                'bcn' => '857603',
            ],
            [
                'name' => 'Travel 4',
                'slug' => 'slug4',
                'app_domain' => 'travel4.demo.prohajj.app',
                'bcn' => '857604',
            ],
            [
                'name' => 'Travel 5',
                'slug' => 'slug5',
                'app_domain' => 'travel5.demo.prohajj.app',
                'bcn' => '857605',
            ],
        ];

        foreach ($seedTenants as $tenant){
            $tenant['wallet_login'] = [
                'WALLET_URL' => "https://demo.biznet.class.id",
                'WALLET_BCN' => "857400",
                'WALLET_ADMIN_USER' => "fahrudinsidik88@gmail.com",
                'WALLET_ADMIN_PASS' => "password",
            ];

            Tenant::query()->create($tenant);
        }
    }
}
