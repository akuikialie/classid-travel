<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Enums\Kuartal;
use App\Http\Controllers\Web\Admin\Controller;
use App\Models\Destination\Destination;
use App\Models\Itinerary\ItineraryActivity;
use App\Models\Plan\Plan;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Services\ItineraryService;
use App\Services\PackageService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class PackageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $user = auth()->user();
        $packages = PlanPackage::query()
            ->with(['myPlan', 'myDestinations'])
//            ->with(['myItineraries.activities'])
            ->withCount(['jamaah'])
            ->tenantId($user->tenant_id)
            ->latest()
            ->get();
//            ->first();

//        $myItineraries = $packages->myItineraries ?? [];
//
//
//        $itinerary = collect($myItineraries)->firstWhere('day', 1);
//        $lastData = [];
//        foreach ($itinerary->activities as $_activity) {
//            $lastData[] = [
//                'time' => $_activity->pivot->time,
//                'activity' => $_activity->activity,
//            ];
//        }
//        dd($lastData);
//        dd($activities->activities->count());
//        dd($activity->)

//        dd($packages);

        return view('pages.web.master.package.package-index', [
            'packages' => $packages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function create(): JsonResponse|Redirector|Application|RedirectResponse
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
                'view' => view('pages.web.master.package.modal.wizard-create-modal', [
                    'plans' => $plans,
                    'facilities' => $facilities,
                    'destinations' => $destinations,
                    'kuartals' => $kuartals,
                ])->render(),
            ]);
        } else {
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.package.index'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request)
    {
        $validator = $this->getArr($request);
        DB::beginTransaction();
        try {

            $user = auth()->user();
            $packageService = new PackageService($user->tenant_id);

            $newPackage = $packageService->createNewPackage($validator['type'], $validator);

            $facilityIds = [];
            if (isset($validator['facilities']) && is_array($validator['facilities'])) {
                $facilityIds = array_keys($validator['facilities']);
//                $packageService->addFacilitiesToPackage($package, $facilityIds);
            }

            $destinationIds = [];
            if (isset($validator['destinations']) && is_array($validator['destinations'])) {
                $destinationIds = array_keys($validator['destinations']);
//                $packageService->addDestinationsToPackage($package, $destinationIds);
            }

            $packageService
                ->byHash($newPackage->hash)
                ->addDestinations($destinationIds)
                ->addFacilities($facilityIds);

            if ($request->hasfile('thumbnail')) {
                $packageService->addThumbnailPackage($newPackage, $request);
            }

            DB::commit();
            notify('Berhasil', 'Data paket berhasil dibuat!', 'success')->autoClose();

            return redirect()->back();
        } catch (Throwable $th) {
            throw  $th;
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Redirector|RedirectResponse
     */
    public function show(int $id): Redirector|RedirectResponse|Application
    {
        notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('master.package.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|JsonResponse|Redirector|RedirectResponse
     */
    public function edit(int $id): JsonResponse|Redirector|RedirectResponse|Application
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
                'view' => view('pages.web.master.package.modal.wizard-edit-modal', [
                    'package' => $package,
                    'plans' => $plans,
                    'facilities' => $facilities,
                    'destinations' => $destinations,
                    'kuartals' => $kuartals,
                ])->render(),
            ]);
        } else {
            notify('Oops!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.package.index'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = $this->getArr($request);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $packageService = new PackageService($user->tenant_id);

            $package = PlanPackage::query()->whereId($id)->first();


            $facilityIds = [];
            if (isset($validator['facilities']) && is_array($validator['facilities'])) {
                $facilityIds = array_keys($validator['facilities']);
//                $packageService->addFacilitiesToPackage($package, $facilityIds);
            }

            $destinationIds = [];
            if (isset($validator['destinations']) && is_array($validator['destinations'])) {
                $destinationIds = array_keys($validator['destinations']);
//                $packageService->addDestinationsToPackage($package, $destinationIds);
            }

            $packageService
                ->byHash($package->hash)
                ->addDestinations($destinationIds)
                ->addFacilities($facilityIds);

            if ($request->hasfile('thumbnail')) {
                $packageService->addThumbnailPackage($package, $request);
            }

            $package->long_days = $validator['long_days'];
            $package->name = $validator['name'];
            $package->description = $validator['description'];
            $package->amount = $validator['amount'];
            $package->push();

            DB::commit();
            notify('Berhasil', 'Data paket berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $th) {
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
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
            notify('Berhasil', 'Data paket berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $th) {
            notify('Oops!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getArr(Request $request): array
    {
        return $request->validate([
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
    }

    public function createSetupItinerary($hash)
    {
        if (\request()->ajax()) {

            $user = auth()->user();
            $package = PlanPackage::query()
                ->with(['myItineraries'])
                ->tenantId($user->tenant_id)
                ->byHashOrFail($hash);

            $myItineraries = $package->myItineraries ?? [];
            $itineraries = ItineraryActivity::query()
                ->tenantId($user->tenant_id)
                ->get();

            return response()->json([
                'view' => view('pages.web.master.itinerary.modal.wizard-setup-itinerary', [
                    'package' => $package,
                    'myItineraries' => $myItineraries,
                    'itineraries' => $itineraries,
                ])->render(),
            ]);
        } else {
            notify('Oops!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.package.index'));
        }
    }

    public function storeSetupItinerary(Request $request, $hash)
    {
        $input = $request->validate([
            'name' => ['required', 'array'],
            'time' => ['required', 'array'],
            'itinerary' => ['required', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $package = PlanPackage::query()
                ->with(['myItineraries'])
                ->tenantId($user->tenant_id)
                ->byHashOrFail($hash);

            if (count($input['time']) !== count($input['itinerary'])) {
                throw new InvalidArgumentException('Terjadi perbedaan input data!. mohon di periksa kembali.', 500);
            }

            $lastData = [];

            $sampleName = $input['name'];
            $sampleTime = $input['time'];
            $sampleItinerary = $input['itinerary'];

            if ((count($sampleTime) == $package->long_days) and (count($sampleItinerary) == $package->long_days)) {
                for ($i = 1; $i <= $package->long_days; $i++) {
                    $timeData = $sampleTime["day-{$i}"];
                    $itineraryData = $sampleItinerary["day-{$i}"];

                    $lastData[$i]['name'] = collect($sampleName["day-{$i}"])->first() ?? "day-{$i}";

                    for ($a = 0; $a < count($timeData); $a++) {
                        if (is_null($timeData[$a]) && is_null($itineraryData[$a])) {
                            continue;
                        }
                        $lastData[$i]['itineraries'][] = [
                            'time' => $timeData[$a],
                            'itinerary' => (int)$itineraryData[$a],
                        ];
                    }
                }
            }

            /* begin:: start itinerary service */
            $itineraryService = new ItineraryService($user->tenant_id);
            $itineraryService
                ->setModel($package)
                ->addItineraries($lastData ?? []);
            /* end:: start itinerary service */

            notify('Berhasil', 'Daftar aktifitas berhasil diperbarui!', 'success')->autoClose();
            DB::commit();
        } catch (Throwable $e) {
            notify('Oops!', $e->getMessage(), 'error');
            DB::rollBack();
        } finally {
            return redirect()->back()->withInput();
        }
    }
}
