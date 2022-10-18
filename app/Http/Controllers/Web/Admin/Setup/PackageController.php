<?php

namespace App\Http\Controllers\Web\Admin\Setup;

use App\Enums\Kuartal;
use App\Http\Controllers\Controller;
use App\Models\Destination\Destination;
use App\Models\Plan\Plan;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

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
            ->with(['myPlan'])
            ->withCount(['jamaah'])
            ->latest()
            ->get();

        return view('pages.web.setup.package.package-index', [
            'packages' => $packages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->ajax()) {

            $plans = Plan::query()
                ->where('is_active', true)
                ->latest('id')
                ->get();

            $facilities = PlanFacility::query()->get();
            $destinations = Destination::query()->get();

            $kuartals = Kuartal::cases();

            return response()->json([
                'view' => view('pages.web.setup.package.modal.wizard-create-modal', [
                    'plans' => $plans,
                    'facilities' => $facilities,
                    'destinations' => $destinations,
                    'kuartals' => $kuartals,
                ])->render(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'type' => ['required', 'string'],
            'departure_year' => ['nullable', 'string'],
            'kuartal' => ['nullable', 'string'],
            'long_days' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'integer'],
            'facilities' => ['nullable', 'array'],
            'destinations' => ['nullable', 'array'],
            'thumbnail' => ['nullable', 'mimes:jpg,png,jpeg', 'max:3072'],
        ]);

        DB::beginTransaction();
        try {
            $newPackage = $this->packageService->createNewPackage($validator['type'], $validator);

            if (isset($validator['facilities']) && is_array($validator['facilities'])) {
                $facilityIds = array_keys($validator['facilities']);
                $this->packageService->addFacilitiesToPackage($newPackage, $facilityIds);
            }

            if (isset($validator['destinations']) && is_array($validator['destinations'])) {
                $destinationIds = array_keys($validator['destinations']);
                $this->packageService->addDestinationsToPackage($newPackage, $destinationIds);
            }

            if ($request->hasfile('thumbnail')) {
                $this->packageService->addThumbnailPackage($newPackage, $request);
            }

            DB::commit();
            return redirect()->back()->with('success', 'work');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $package = PlanPackage::query()
                ->with(['myFacilities', 'myDestinations'])
                ->whereId($id)->first();

            $plans = Plan::query()
                ->where('is_active', true)
                ->latest('id')
                ->get();

            $facilities = PlanFacility::query()->get();
            $destinations = Destination::query()->get();

            $kuartals = Kuartal::cases();

            return response()->json([
                'view' => view('pages.web.setup.package.modal.wizard-edit-modal', [
                    'package' => $package,
                    'plans' => $plans,
                    'facilities' => $facilities,
                    'destinations' => $destinations,
                    'kuartals' => $kuartals,
                ])->render(),
            ]);
        }
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
        $validator = $request->validate([
            'type' => ['required', 'string'],
            'departure_year' => ['nullable', 'string'],
            'kuartal' => ['nullable', 'string'],
            'long_days' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'integer'],
            'facilities' => ['nullable', 'array'],
            'destinations' => ['nullable', 'array'],
            'thumbnail' => ['nullable', 'mimes:jpg,png,jpeg', 'max:3072'],
        ]);

        DB::beginTransaction();
        try {
            $package = PlanPackage::query()->whereId($id)->first();


            if (isset($validator['facilities']) && is_array($validator['facilities'])) {
                $facilityIds = array_keys($validator['facilities']);
                $this->packageService->addFacilitiesToPackage($package, $facilityIds);
            }

            if (isset($validator['destinations']) && is_array($validator['destinations'])) {
                $destinationIds = array_keys($validator['destinations']);
                $this->packageService->addDestinationsToPackage($package, $destinationIds);
            }

            if ($request->hasfile('thumbnail')) {
                $this->packageService->addThumbnailPackage($package, $request);
            }

            $package->long_days = $validator['long_days'];
            $package->name = $validator['name'];
            $package->description = $validator['description'];
            $package->amount = $validator['amount'];
            $package->push();

            DB::commit();
            return redirect()->back()->with('success', 'work');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $package = PlanPackage::query()
                ->withCount(['jamaah', 'myDestinations', 'myFacilities'])
                ->whereId($id)->first();

            if ($package->jamaah_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus paket, karena paket ini sedang digunakan!', 500);
            }
            if ($package->my_destinations_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus paket, karena paket ini sedang digunakan!', 500);
            }
            if ($package->my_facilities_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus paket, karena paket ini sedang digunakan!', 500);
            }

            $package->delete();
            return redirect()->back()->with('success', 'work');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
