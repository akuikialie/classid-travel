<?php

namespace App\Actions\Users;

use App\Enums\RoleEnum;
use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CreateNewUser
{
    public function handle(array $input): array
    {
        $user = User::query()->create(array_merge($input, [
            'tenant_id' => $input['tenant_id'],
            'password' => Hash::make($input['password'])
        ]));

        $user->syncRoles(RoleEnum::Jamaah->keyValue());

        $VA = VirtualAccount::query()
            ->where(function ($subQuery) {
                $subQuery->where('va_label', 'tabungan')
                    ->whereMonth('created_at', Carbon::now());
            })->max('va_number');

        $newVANumber = createNewVA('tabungan', $VA);
        $newVA = new VirtualAccount([
            'tenant_id' => 1,
            'va_number' => $newVANumber,
            'va_label' => 'tabungan',
        ]);

        $user->tabungan()->save($newVA);
        $user->save();

        $newJamaah = new Jamaah(
            [
                'tenant_id' => 1,
            ]
        );
        $user->jamaah()->save($newJamaah);

        return [
            'user' => $user,
            'jamaah' => $newJamaah,
            'va' => $newVA
        ];
    }
}
