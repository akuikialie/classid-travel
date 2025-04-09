<?php

namespace Database\Seeders;

use App\Services\UserService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $seedUser = [
            [
                'name' => 'Dev Super Admin Account',
                'phone' => '0831',
                'username' => 'admin',
                'password' => 'admin',
                'role' => 'super-administrator',
            ],
            [
                'tenant_id' => 6,
                'name' => 'Dev Admin Account',
                'phone' => '0832',
                'username' => 'admin',
                'password' => 'admin',
                'role' => 'administrator',
            ],
        ];

        foreach ($seedUser as $key => $input) {
            DB::beginTransaction();
            try {
                /* begin:: user service */
                $userService = new UserService($input['tenant_id'] ?? null);
                $userService
                    ->createNewUser(
                        collect($input)->forget('role')->toArray(),
                        ($input['role'] == 'jamaah'))
                    ->setRole($input['role'])
                    ->getUser();
                /* end:: user service */
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->command->info($e->getMessage());
            }

        }
    }
}
