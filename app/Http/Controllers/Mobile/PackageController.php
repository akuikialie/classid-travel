<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\Statuses;
use App\Exceptions\HandleCatchableException;
use App\Models\Geo\City;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\Schedule\Schedule;
use App\Services\JamaahService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class PackageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $with = ['myPlan:id,value', 'myFacilities', 'myDestinations', 'myItineraries' => function ($subQuery) {
            $subQuery->orderBy('day', 'asc');
        }, 'myItineraries.activities', 'media'
        ];
        $withCount = ['myFacilities', 'myDestinations', 'myItineraries'];

        $user = auth()->user();
        $packages = PlanPackage::query()
            ->tenantId($user->tenant_id)
            ->with($with)
            ->withCount($withCount)
            ->where(function ($subQuery) {
                $subQuery->where('is_publish', true);
                $subQuery->where('status', Statuses::Active->keyValue());
            })
            ->get();

        return view('pages.mobile.package.package-index', ['packages' => $packages]);
    }

    /**
     * @throws Throwable
     * @throws HandleCatchableException
     */
    public function addPackageToJamaah(Request $request, $package_id)
    {
        $validator = $request->validate([
            'departure_city_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();

            $package = PlanPackage::query()->where('id', $package_id)->first();
            $jamaah = Jamaah::query()
                ->with(['planPackages'])
                ->where('user_id', $user->id)->first();

            /* begin:: validation */
            // check package on jamaah
            $existingPackageInJamaah = collect($jamaah->planPackages)->where('id', $package->id)->first();
            if ($existingPackageInJamaah) {
                throw HandleCatchableException::catchable('Kamu sudah mengambil paket ini!.');
            }
            /* end:: validation */

            /* begin:: add package to jamaah */
            (new JamaahService($user->tenant_id))
                ->setPackage(package: $package)
                ->setJamaah(jamaah: $jamaah)
                ->addPackage()
                ->linkDeparture([
                    'departure_city_id' => $validator['departure_city_id'],
                    'schedule_id' => $validator['schedule_id'],
                ]);
            /* end:: add package to jamaah */

            DB::commit();
            notify('Berhasil', "Paket  {$package->name} berhasil ditambahkan!.", 'success');
            return redirect(route('tabungan.index'));
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Mobile package');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id): View|Factory|Application
    {
        $with = ['myFacilities', 'myDestinations', 'myItineraries' => function ($subQuery) {
            $subQuery->orderBy('day', 'asc');
        }, 'myItineraries.activities', 'media'
        ];

        $withCount = ['myFacilities', 'myDestinations', 'myItineraries'];
        $package = PlanPackage::query()
            ->with($with)
            ->withCount($withCount)
            ->whereId($id)->first();

        $user = auth()->user();

        $schedules = Schedule::query()
            ->tenantId($user->tenant_id)
            ->whereDate('departure_date', '>', Carbon::now()->addDay(7))
            ->get();

        $cities = City::query()->get();

        return view('pages.mobile.package.package-show', [
            'package' => $package,
            'cities' => $cities,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
