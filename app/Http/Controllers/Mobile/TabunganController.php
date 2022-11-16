<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;

class TabunganController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        /* begin:: show all savings */
        $savings = $this->listSavings($user);
        /* end:: show all savings */

        return view('pages.mobile.tabungan.tabungan-index', [
            'list_moneyboxs' => $savings,
        ]);
    }

    public function show(VirtualAccount $virtualAccount)
    {

        switch ($virtualAccount->va_label) {
            case 'tabungan':
                $saving = [
                    'id' => $virtualAccount->id,
                    'va' => $virtualAccount->va_number,
                ];
                break;

            case 'perencanaan':
                $name = 'tabungan ' . $virtualAccount?->myPackage->name;
                $saving = [
                    'namaTabungan' => ucwords($name ?? 'NN'),
                    'id' => $virtualAccount->id,
                    'va' => $virtualAccount->va_number,
                    'targetSavings' => 'Rp ' . number_format($virtualAccount->myPackage?->amount ?? 0),
                ];
                break;

            default:
                # code...
                break;
        }
        return view('pages.mobile.tabungan.tabungan-show', [
            'moneybox' => collect($saving),
        ]);
    }


    private function listSavings(User $authUser)
    {
        $savings = collect([]);
        /* begin:: main savings */
        $user = User::query()
            ->with(['tabungan'])
            ->where('id', '=', $authUser->id)
            ->first();

        $mainSaving = [
            'id' => $user->tabungan->hash,
            'va' => $user->tabungan->va_number,
            'showDetails' => true,
        ];

        $savings->add($mainSaving);
        /* end:: main savings */

        /* begin:: planing savings */
        $jamaah = Jamaah::query()
            ->with(['tabunganPackages.myPackage.myPlan'])
            ->where('user_id', '=', $authUser->id)
            ->first();

        $planingSavings = [];
        foreach ($jamaah->tabunganPackages as $tabungan) {

            $namaTabungan = 'tabungan ' . $tabungan?->myPackage->name;
            $savings->add([
                'namaTabungan' => ucwords($namaTabungan),
                'id' => $tabungan->hash,
                'va' => $tabungan->va_number,
                'targetSavings' => $tabungan?->myPackage?->amount ?? 0,
                'showDetails' => true,
            ]);
        }
        /* end:: planing savings */

        return $savings;
    }
}
