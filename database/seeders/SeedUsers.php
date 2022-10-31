<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Jamaah\Jamaah;
use App\Models\Tenant\Tenant;
use App\Models\User;
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
        $seedUser = [
            [
                'tenant_id' => 1,
                'name' => 'winata bayu',
                'username' => 'winata',
                'phone' => '081331307327',
                'password' => Hash::make('bayu'),
                'role' => RoleEnum::Admin->keyValue(),
            ], [
                'tenant_id' => 2,
                'name' => 'bayu winata',
                'username' => 'wb',
                'phone' => '081331307328',
                'password' => Hash::make('bayu'),
                'role' => RoleEnum::Jamaah->keyValue(),
            ],
        ];

        foreach ($seedUser as $key => $user) {
            \DB::beginTransaction();
            $newUser = null;
            try {
                $newUser = User::create([
                    'tenant_id' => $user['tenant_id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
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
                $VAService = new VirtualAccountService();

                try {
                    $VAService->vaType('tabungan')
                        ->createFor($newUser->fresh(['tenant']))
                        ->createVA();
                } catch (DdException|\Throwable $e) {
                    $this->command->info($e->getMessage());
                }
                /* end:: start Virtual Account Service */
            }

        }


    }
}
