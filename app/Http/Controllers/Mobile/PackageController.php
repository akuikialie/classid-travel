<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\Statuses;
use App\Enums\VirtualAccount;
use App\Http\Controllers\Controller;
use App\Jobs\Plan\Package\AddPackageToJamaah;
use App\Models\Geo\City;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\Schedule\Schedule;
use App\Models\User;
use App\Services\PackageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{

    protected PackageService $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = PlanPackage::query()
            ->with(['myPlan:id,value', 'myFacilities', 'myDestinations', 'media'])
            ->withCount(['myFacilities', 'myDestinations'])
            ->where(function ($subQuery) {
                $subQuery->where('is_publish', true);
                $subQuery->where('status', Statuses::tryFrom('active')->keyValue());
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
            $package = PlanPackage::query()->where('id', $package_id)->first();
            $jamaah = Jamaah::query()
                ->with(['planPackages'])
                ->where('user_id', auth()->user()->id)->first();

            /* add package to jamaah */
            $this->packageService->addPackageToJamaah($package, $jamaah);

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
        } catch (\Throwable $th) {
            DB::rollBack();

            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $package = PlanPackage::query()
            ->with(['myFacilities', 'myDestinations'])
            ->whereId($id)->first();

        $schedules = Schedule::query()
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
