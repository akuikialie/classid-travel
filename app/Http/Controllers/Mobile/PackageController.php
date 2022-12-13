<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\Statuses;
use App\Models\Geo\City;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\Schedule\Schedule;
use App\Services\PackageService;
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
        }, 'myItineraries.activities', 'media'];
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

            /* add package to jamaah */
            $packageService = new PackageService($user->tenant_id);
            $packageService->addPackageToJamaah($package, $jamaah);

            /* link tempat keberangkatan */
            $city = City::query()->find($validator['departure_city_id']);
            $jamaah->departureCity()->associate($city);
            /* link tanggal keberangkatan */
            $schedule = Schedule::query()->find($validator['schedule_id']);
            $jamaah->departureSchedule()->associate($schedule);
            $jamaah->push();

            DB::commit();
            notify('Berhasil', "Paket  {$package->name} berhasil ditambahkan!.", 'success');
            return redirect(route('tabungan.index'));
        } catch (Throwable $e) {
            DB::rollBack();

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
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id): View|Factory|Application
    {
        $with = ['myFacilities', 'myDestinations', 'myItineraries' => function ($subQuery) {
            $subQuery->orderBy('day', 'asc');
        },
            'myItineraries.activities', 'media'];

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
