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

//        for ($i = 1; $i <= 5; $i++){
//            $admins[] = [
//                'tenant_id' => $i,
//                'name' => 'travel admin ' . $i,
//                'phone' => '08111111111'. $i,
//                'password' => 'admin',
//                'role' => 'administrator',
//            ];
//
//           /* $apps[] = [
//                'tenant_id' => $i,
//                'name' => 'Travel Apps ' . $i,
//                'phone' => '08222222222'. $i,
//                'password' => Hash::make('app'),
//                'role' => RoleEnum::Jamaah->keyValue(),
//            ];*/
//        }

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

//            if (isset($input['tenant_id'])) {
//                if ($newUser instanceof User) {
//                    /* begin:: start Virtual Account Service */
//                    $VAService = new VirtualAccountService($input['tenant_id']);
//
//                    try {
//                        $VAService->vaType('tabungan')
//                            ->createFor($newUser)
//                            ->createVA();
//                    } catch (DdException|\Throwable $e) {
//                        $this->command->info($e->getMessage());
//                    }
//                    /* end:: start Virtual Account Service */
//                }
//            }


        }
    }
}
