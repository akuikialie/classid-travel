<?php

namespace Database\Seeders;

use App\Models\Jamaah\Jamaah;
use App\Models\Spatie\Role;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'name' => 'winata bayu',
                'username' => 'winata',
                'phone' => '081331307327',
                'password' => Hash::make('bayu'),
            ], [
                'name' => 'winata bayu',
                'username' => 'wb',
                'phone' => '081331307328',
                'password' => Hash::make('bayu'),
            ],
        ];

        foreach ($seedUser as $key => $user) {
            $user = User::query()->create($user);

            if ($user['username'] == 'winata') {
                $user->syncRoles([Role::RoleSA]);
            }

            $VA = VirtualAccount::query()
                ->where(function ($subQuery) {
                    $subQuery->where('va_label', 'tabungan')
                        ->whereMonth('created_at', Carbon::now());
                })->max('va_number');

            $newVANumber = createNewVA('tabungan', $VA);
            $newVA = new VirtualAccount([
                'va_number' => $newVANumber,
                'va_label' => 'tabungan',
            ]);

            $user->tabungan()->save($newVA);
            $user->save();

            $newJamaah = new Jamaah();
            $user->jamaah()->save($newJamaah);
        }


    }
}
