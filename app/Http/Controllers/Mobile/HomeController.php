<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Jamaah\Jamaah;
use App\Models\Tenant\Tenant;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {

        $user = auth()->user();
        /* begin:: show all savings */
        $savings = $this->listSavings($user);
        /* end:: show all savings */

        $tenant = Tenant::query()
            ->with(['media'])
            ->where('id', $user->tenant_id)
            ->first();

        $mediaBanners = $tenant->getMedia('banners');
        $bannerCollections = [];
        foreach ($mediaBanners as $item){
            $bannerCollections[] = [
                'image_url' => $item->getUrl(),
                'order' => $item->getCustomProperty('order'),
            ];
        }

        return view('pages.mobile.home.dashboard-index', [
            'data' => collect([
                'name' => $user->name,
                'phone' => $user->phone,
                'totalSavings' => 'Rp '. number_format($totalSavings ?? 0),
                'targetSavings' => 'Rp '. number_format($savings->sum('targetSavings')),
            ]),
            'list_moneyboxs' => $savings,
            'total_tabungan' => $savings->count(),
            'banners' => $bannerCollections ?? [],
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
