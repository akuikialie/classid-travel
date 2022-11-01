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

        for ($i = 1; $i <= 5; $i++){
            $admins[] = [
                'tenant_id' => $i,
                'name' => 'travel admin ' . $i,
                'phone' => '08111111111'. $i,
                'password' => Hash::make('admin'),
                'role' => RoleEnum::Admin->keyValue(),
            ];

            $apps[] = [
                'tenant_id' => $i,
                'name' => 'Travel Apps ' . $i,
                'phone' => '08222222222'. $i,
                'password' => Hash::make('app'),
                'role' => RoleEnum::Jamaah->keyValue(),
            ];
        }

        $seedUser = array_merge($admins, $apps);

        foreach ($seedUser as $key => $user) {
            \DB::beginTransaction();
            $newUser = null;
            try {
                $newUser = User::create([
                    'tenant_id' => $user['tenant_id'],
                    'name' => $user['name'],
                    'phone' => $user['phone'],
                    'password' => $user['password'],
                ]);

                $newUser->syncRoles([$user['role']]);

                $tenant = Tenant::query()->find($user['tenant_id']);
                $newUser->tenant()->associate($tenant);

                $newJamaah = new Jamaah([
                    'tenant_id' => $tenant->id,
                ]);
                $newUser->jamaah()->save($newJamaah);

                $newUser->push();

                \DB::commit();
            }catch (\Throwable $e){
                \DB::rollBack();
                $this->command->info($e->getMessage());
            }

            if ($newUser instanceof User) {
                /* begin:: start Virtual Account Service */
                $VAService = new VirtualAccountService($user['tenant_id']);

                try {
                    $VAService->vaType('tabungan')
                        ->createFor($newUser)
                        ->createVA();
                } catch (DdException|\Throwable $e) {
                    $this->command->info($e->getMessage());
                }
                /* end:: start Virtual Account Service */
            }

        }

//        /* begin:: user service */
//        $userService = new UserService(tenantId: $user['tenant_id']);
//        $userService
//            ->createNewUser($input)
//            ->setRole(RoleEnum::Jamaah->keyValue())
//            ->createVa('tabungan')
//            ->setDepartureStatus();
//        /* end:: user service */


    }
}
