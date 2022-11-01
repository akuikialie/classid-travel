<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Jamaah\Jamaah;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dataKeberangkatan = Jamaah::query()
            ->where('id', '!=', $user->id)
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
