<?php

namespace Database\Seeders;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Seeder;

class SeedInsertCredentials extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /* begin:: insert wallet credentials */
        Tenant::query()
            ->where('is_active',true)
            ->update([
                'wallet_login' => [
                    'WALLET_URL' => "https://demo.biznet.class.id",
                    'WALLET_BCN' => "857400",
                    'WALLET_ADMIN_USER' => "fahrudinsidik88@gmail.com",
                    'WALLET_ADMIN_PASS' => "password",
                ],
            ]);
        /* end:: insert wallet credentials */
    }
}
