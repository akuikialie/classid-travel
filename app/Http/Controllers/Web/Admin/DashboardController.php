<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Jamaah\Jamaah;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class DashboardController extends Controller
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setBreadCrumb(['title' => 'Travel', 'url' => routed('admin.tenant.index')]);
    }

    /**
     * @return Application|Factory|View|RedirectResponse|Redirector|void
     */
    public function index()
    {
        $this->setPageTitle('Users');
        $this->setBreadCrumb('Users');

        $user = auth()->user();

        if ($user->hasRole('administrator')){
            return $this->dashboardAdmin();
        }else if ($user->hasRole('super-administrator')){
            return $this->dashboardSuperAdmin();
        }
    }

    public function dashboardSuperAdmin()
    {
        return redirect(route('admin.tenant.index'));
//        return view('pages.web.dashboard.dashboard-super-admin-index');
    }

    public function dashboardAdmin(): Application|Factory|View
    {
        $this->setPageTitle('Users');

        $user = auth()->user();
        $dataKeberangkatan = Jamaah::query()
            ->tenantId($user->tenant_id)
            ->with([
                'user',
                'departureSchedule',
                'planPackages',
                'departureHistory',
            ])
            ->latest()
            ->get();

        $arrayData = [];
        foreach ($dataKeberangkatan as $item) {
            if (!$item->user){
                continue;
            }
            $arrayData[] = [
                'id' => $item->id,
                'name' => $item->user?->name,
                'departure_date' => $item->departureSchedule?->departure_date,
                'plan' => collect($item->planPackages)->first()?->name, // plan package name
                'departure_status' => collect($item->departureHistory)->first()?->departure_status,
                'plan_long_days' => collect($item->planPackages)->first()?->long_days,
            ];
        }

        return view('pages.web.dashboard.dashboard-index', [
            'data_keberangkatan' => array_to_object($arrayData),
        ]);
    }
}
