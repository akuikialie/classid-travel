<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Tenant\Tenant;
use App\Models\User;
use App\Services\Saving\SavingService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws \Exception
     */
    public function index(SavingService $service)
    {
        /** @var User $user */
        $user = auth()->user();

        $savings = $service->getListSavings($user);

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

        $totalSavings = $user->tabungan->getStartingBalance();

        return view('pages.mobile.home.dashboard-index', [
            'data' => collect([
                'name' => $user->name,
                'phone' => $user->phone,
                'totalSavings' => 'Rp '. number_format($totalSavings ?? 0),
                'totalUsdSavings' => '$ '. number_format($user->tabungan->usd_balance ?? 0),
                'targetSavings' => 'Rp '. number_format($savings->sum('targetSavings')),
            ]),
            'list_moneyboxs' => $savings,
            'total_tabungan' => $savings->count(),
            'banners' => $bannerCollections ?? [],
        ]);
    }
}
