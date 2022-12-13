<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Jamaah\Jamaah;
use App\Models\Tenant\Tenant;
use App\Models\User;
use App\Services\UserService;
use App\Services\VirtualAccountService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Octane\Exceptions\DdException;

class SeedUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admins = [
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

        $seedUser = $admins;

        foreach ($seedUser as $key => $input) {
            \DB::beginTransaction();
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
                \DB::commit();
            }catch (\Throwable $e){
                \DB::rollBack();
                $this->command->info($e->getMessage());
            }

        }
    }
}
