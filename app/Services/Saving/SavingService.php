<?php

namespace App\Services\Saving;

use App\Models\Jamaah\Jamaah;
use App\Models\User;

class SavingService
{
    public function getListSavings(User $user)
    {
        $savings = collect([]);
        /* begin:: main savings */
        $user->loadMissing('tabungan');

        $totalSavings = $user->tabungan->getStartingBalance();
        $mainSaving = [
            'id' => $user->tabungan->id,
            'va' => $user->tabungan->va_number,
            'savings' => 'Rp '. number_format($totalSavings ?? 0),
            'usd_savings' => '$ '. number_format($user->tabungan->usd_balance ?? 0),
            'showDetails' => true,
        ];

        $savings->add($mainSaving);
        /* end:: main savings */

        /* begin:: planing savings */
        $jamaah = Jamaah::query()
            ->with(['tabunganPackages.myPackage.myPlan'])
            ->where('user_id', '=', $user->id)
            ->first();

        foreach ($jamaah->tabunganPackages as $tabungan) {
            $namaTabungan = 'tabungan ' . $tabungan?->myPackage->name;
            $totalSavings = $tabungan->getStartingBalance();
            $savings->add([
                'namaTabungan' => ucwords($namaTabungan),
                'id' => $tabungan->hash,
                'va' => $tabungan->va_number,
                'savings' => 'Rp '. number_format($totalSavings ?? 0),
                'usd_savings' => '$ '. number_format($tabungan->usd_balance ?? 0),
                'targetSavings' => $tabungan?->myPackage?->amount ?? 0,
                'showDetails' => true,
            ]);
        }
        /* end:: planing savings */

        return $savings;
    }
}
