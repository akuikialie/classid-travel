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
                'view' => view('pages.web.setup.package.modal.wizard-setup-modal', [
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
            'departure_year' => ['required', 'string'],
            'kuartal' => ['required', 'string'],
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
