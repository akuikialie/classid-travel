<?php

namespace App\Http\Controllers;

use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    protected $moneyboxs = []; // tabungan
    protected $moneyboxsHistories = []; // history tabungan

    public function __construct()
    {
        $this->moneyboxs = [
            [
                'id' => 1,
                'savings' => '900.000',
                'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                'showDetails' => true,
            ],
            [
                'id' => 2,
                'namaTabungan' => 'Tabungan Umrah 2024',
                'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                'savings' => '12.500.000',
                'targetSavings' => '32.000.000',
                'showDetails' => true,
            ],
            [
                'id' => 3,
                'namaTabungan' => 'Tabungan Wisata',
                'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                'savings' => '890.000',
                'targetSavings' => '2.000.000',
                'showDetails' => true,
            ]
        ];
    }

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
            ->with(['tabunganPackages.myPackage.myPlan'])
            ->where('user_id', '=', auth()->user()->id)
            ->first();

        $semuaTabungan = array_merge($semuaTabungan, $tabunganPribadi);
        $tabunganPerencanaan = [];
        foreach ($jamaah->tabunganPackages as $key => $tabungan) {
            $namaTabungan = 'tabungan ' . $tabungan?->myPackage?->myPlan?->value;
            $tabunganPerencanaan[] = [
                'namaTabungan' => ucwords($namaTabungan) . ' 2024',
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
