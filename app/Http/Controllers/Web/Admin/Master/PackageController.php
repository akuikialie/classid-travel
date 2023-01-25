<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Enums\Kuartal;
use App\Enums\Statuses;
use App\Http\Controllers\Web\Admin\Controller;
use App\Models\Destination\Destination;
use App\Models\Itinerary\ItineraryActivity;
use App\Models\Plan\Plan;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Services\ItineraryService;
use App\Services\PackageService;
use Exception;
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
use Laravel\Octane\Exceptions\DdException;
use Throwable;

class PackageController extends Controller
{

    protected string $forPage = 'package';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @return JsonResponse|void
     * @throws Throwable
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    public function datatable()
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $packages = PlanPackage::query()
                    ->with(['myPlan', 'myDestinations'])
                    ->withCount(['jamaah'])
                    ->tenantId($user->tenant_id)
                    ->latest();

                $datatable = datatables()->eloquent($packages)
                    ->addIndexColumn()
                    ->addColumn('name', function ($package) {
                        return $package->name;
                    })->addColumn('plan', function ($package) {
                        return ($package->myPlan->value ?? '-');
                    })->addColumn('description', function ($package) {
                        $desc = mb_strimwidth(($package->description ?? '-'), 0, 20, '...');
                        if (strlen($package->description) > 20){
                            $desc .= '<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                       title="'. $package->description. '"></i>';
                        }
                        return $desc ;
                    })->addColumn('price', function ($package) {
                        return rupiahFormat($package->amount);
                    })->addColumn('status', function ($package) {
                        $status = Statuses::tryFrom($package->status);
                        $publish = ($package->is_publish ? 'Published' : 'Unpublished');
                        return "
                <div class=\"text-dark fw-bold text-hover-primary fs-6\">
                  <span class=\"badge badge-light-{$status->color()} text-uppercase\">{$status->label()}</span>
                  <span
                    class=\"text-muted fw-semibold text-muted d-block fs-7\">{$publish}
                  </span>
                </div>";
                    })->addColumn('long_days', function ($package) {
                        return $package->long_days;
                    })
                    ->addColumn('actions', function ($package) {
                        $this->setData('package', $package);
                        return $this->view('pages.web.master.package.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status', 'description']);

                return $datatable->make(true);
            } catch (Throwable $e) {
                logError($e, title: 'Package');
                if (isDevelopmentMode()) {
                    throw $e;
                } else {
                    throw new Exception('Terjadi kesalahan!');
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $this->setPageTitle('Paket');
        $this->setBreadCrumb('Paket');
        return $this->view('pages.web.master.package.package-index');
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

            $user = auth()->user();
            $facilities = PlanFacility::query()
                ->tenantId($user->tenant_id)
                ->where('is_active', true)
                ->get();
            $destinations = Destination::query()
                ->tenantId($user->tenant_id)
                ->where('is_active', true)
                ->get();

            return response()->json([
                'view' => view('pages.web.master.package.modal.wizard-create-modal', [
                    'plans' => $plans,
                    'facilities' => $facilities,
                    'destinations' => $destinations,
                ])->render(),
            ]);
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $input = $this->getArr($request);
        DB::beginTransaction();
        try {

            $user = auth()->user();

            $facilityIds = [];
            if (isset($input['facilities']) && is_array($input['facilities'])) {
                $facilityIds = array_keys($input['facilities']);
            }

            $destinationIds = [];
            if (isset($input['destinations']) && is_array($input['destinations'])) {
                $destinationIds = array_keys($input['destinations']);
            }

            (new PackageService($user->tenant_id))
                ->createNewPackage($input['type'], $input)
                ->addDestinations($destinationIds)
                ->addFacilities($facilityIds)
                ->addThumbnailPackage($request);

            DB::commit();
            notify('Berhasil', 'Data paket berhasil dibuat!', 'success')->autoClose();

            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Package');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param PlanPackage $package
     * @return Application|Redirector|RedirectResponse
     */
    public function show(PlanPackage $package): Redirector|RedirectResponse|Application
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PlanPackage $package
     * @return Application|JsonResponse|Redirector|RedirectResponse
     */
    public function edit(PlanPackage $package): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (request()->ajax()) {
            $user = auth()->user();

            $plans = Plan::query()
                ->where('is_active', true)
                ->latest('id')
                ->get();

            $facilities = PlanFacility::query()
                ->tenantId($user->tenant_id)
                ->where('is_active', true)
                ->get();

            $destinations = Destination::query()
                ->tenantId($user->tenant_id)
                ->where('is_active', true)
                ->get();

            setDefaultRequest([
                'long_days' => $package?->long_days ?? 0,
            ]);

            return response()->json([
                'view' => view('pages.web.master.package.modal.wizard-edit-modal', [
                    'package' => $package,
                    'plans' => $plans,
                    'facilities' => $facilities,
                    'destinations' => $destinations,
                ])->render(),
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PlanPackage $package
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request, PlanPackage $package): RedirectResponse
    {
        $input = $this->getArr($request);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $packageService = new PackageService($user->tenant_id);

            $facilityIds = [];
            if (isset($input['facilities']) && is_array($input['facilities'])) {
                $facilityIds = array_keys($input['facilities']);
            }

            $destinationIds = [];
            if (isset($input['destinations']) && is_array($input['destinations'])) {
                $destinationIds = array_keys($input['destinations']);
            }

            $input = collect($input)
                ->forget('destinations')
                ->forget('facilities')->toArray();

            $packageService
                ->setPackage($package)
                ->addDestinations($destinationIds)
                ->addFacilities($facilityIds)
                ->addThumbnailPackage($request)
                ->updateExistingPackage($input);

            DB::commit();
            notify('Berhasil', 'Data paket berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Package');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PlanPackage $package
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(PlanPackage $package): RedirectResponse
    {
        try {
            if ($package->jamaah_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus paket, karena paket ini sedang digunakan!', 500);
            }

            $package->delete();
            notify('Berhasil', 'Data paket berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'Package');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
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
            'type' => ['nullable', 'string'],
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
        }
        abort(404);
    }

    /**
     * @param Request $request
     * @param $hash
     * @return RedirectResponse
     * @throws Throwable
     */
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

                    $count = count($timeData);

                    for ($a = 0; $a < $count; $a++) {
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

            return redirect()->back()->withInput();
        } catch (Throwable $e) {
            logError($e, title: 'Package');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @param PlanPackage $package
     * @return RedirectResponse
     * @throws Throwable
     */
    public function changeStatus(Request $request, PlanPackage $package)
    {
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        /* begin:: start tenant service */
        try {
            $user = auth()->user();
            $packageService = new PackageService($user->tenant_id);
            if ($request->has('status')) {
                $packageService
                    ->setPackage($package)
                    ->setStatus($request->get('status'));
                notify('Berhasil!', "Status telah berubah", 'success');
                DB::commit();
            }else{
                throw new InvalidArgumentException('Tidak ada yang berubah!');
            }
            return redirect()->back();
        }catch (Throwable $e){
            logError($e, title: 'Package');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
        /* end:: start tenant service */
    }
}
