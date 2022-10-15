<?php

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;

class VirtualAccountService
{
    public function __construct()
    {
        //
    }

    public function createVirtualAccount($va_type, Jamaah $jamaah = null, PlanPackage $planPackage = null)
    {
        try {
            /* create new VA */
            $VA = VirtualAccount::query()
                ->where(function ($subQuery) use($va_type) {
                    $subQuery->where('va_label', $va_type)
                        ->whereMonth('created_at', Carbon::now());
                })->max('va_number');

            $newVANumber = createNewVA($va_type, $VA);

            switch ($va_type) {
                case 'tabungan':
                    $user = User::query()->find(auth()->user()->id);
                    $newVA = new VirtualAccount([
                        'va_number' => $newVANumber,
                        'va_label' => $va_type,
                    ]);

                    $user->tabungan()->save($newVA);
                    $user->save();
                    break;

                case 'perencanaan':
                    $newVA = new VirtualAccount([
                        'va_number' => $newVANumber,
                        'va_label' => $va_type,
                    ]);

                    $jamaah->tabunganPackages()->save($newVA);

                    $newVA->myPackage()->associate($planPackage);
                    $newVA->save();
                    break;

                default:
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
