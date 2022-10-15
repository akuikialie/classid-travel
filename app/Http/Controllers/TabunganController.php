<?php

namespace App\Http\Controllers;

use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TabunganController extends Controller
{

    public function index()
    {
        /* tampilkan semua tabungan yang ada */

        /* tabungan pribadi */
        $semuaTabungan = [];

        $user = User::query()
            ->with(['tabungan'])
            ->where('id', '=', auth()->user()->id)
            ->first();

        $tabunganPribadi = [[
            'id' => $user->tabungan->id,
            'va' => $user->tabungan->va_number,
            'showDetails' => true,
        ]];

        /* tabungan perencanaan */
        $jamaah = Jamaah::query()
            ->with(['tabunganPackages.myPackage.myPlan', 'departureSchedule'])
            ->where('user_id', '=', auth()->user()->id)
            ->first();

        $semuaTabungan = array_merge($semuaTabungan, $tabunganPribadi);
        $tabunganPerencanaan = [];
        foreach ($jamaah->tabunganPackages as $key => $tabungan) {
            $namaTabungan = 'tabungan ' . $tabungan?->myPackage?->name;
            $tabunganPerencanaan[] = [
                'namaTabungan' => ucwords($namaTabungan) . ' ' . Carbon::parse($jamaah->departureSchedule?->departure_date)->format('Y'),
                'id' => $tabungan->id,
                'va' => $tabungan->va_number,
                'targetSavings' => 'Rp ' . number_format($tabungan?->myPackage?->amount),
                'showDetails' => true,
            ];
        }

        $semuaTabungan = array_merge($semuaTabungan, $tabunganPerencanaan);

        return view('pages.mobile.tabungan.tabungan-index', [
            'list_moneyboxs' => collect($semuaTabungan),
        ]);
    }

    public function show($id)
    {
        $VA = VirtualAccount::query()
            ->with(['myPackage'])
            ->where('id', $id)->first();

        switch ($VA->va_label) {
            case 'tabungan':
                $tabungan = [
                    'id' => $VA->id,
                    'va' => $VA->va_number,
                ];
                break;

            case 'perencanaan':
                $namaTabungan = 'tabungan ' . $VA?->myPackage?->myPlan?->value;
                $tabungan = [
                    'namaTabungan' => ucwords($namaTabungan) . ' 2024',
                    'id' => $VA->id,
                    'va' => $VA->va_number,
                    'targetSavings' => 'Rp ' . number_format($VA?->myPackage?->amount),

                ];
                break;

            default:
                # code...
                break;
        }
        return view('pages.mobile.tabungan.tabungan-show', [
            'moneybox' => collect($tabungan),
        ]);
    }
}
