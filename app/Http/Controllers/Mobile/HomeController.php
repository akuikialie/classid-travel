<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Jamaah\Jamaah;
use App\Models\Tenant\Tenant;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
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
        $targetSavings = 0;
        $totalSavings = 0;
        $totalTabungan = 1;
        foreach ($jamaah->tabunganPackages as $key => $tabungan) {
            $totalTabungan++;
            $amount = $tabungan?->myPackage?->amount;
            $targetSavings = ($targetSavings + intval($amount));
            $namaTabungan = 'tabungan ' . $tabungan?->myPackage?->myPlan?->value;
            $tabunganPerencanaan[] = [
                'namaTabungan' => ucwords($namaTabungan) . ' 2024',
                'id' => $tabungan->id,
                'va' => $tabungan->va_number,
                'targetSavings' => 'Rp ' . number_format($tabungan?->myPackage?->amount),
                'showDetails' => true,
            ];
        }

        $user = auth()->user();
        $tabunganUtama = array_merge($semuaTabungan, $tabunganPerencanaan);

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
                'totalSavings' => 'Rp '. number_format($totalSavings),
                'targetSavings' => 'Rp '. number_format($targetSavings),
            ]),
            'list_moneyboxs' => collect($semuaTabungan),
            'total_tabungan' => $totalTabungan,
            'banners' => $bannerCollections ?? [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
