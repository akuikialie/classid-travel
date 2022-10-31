<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Enums\Kuartal;
use App\Http\Controllers\Controller;
use App\Models\Destination\Destination;
use App\Models\Plan\Plan;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
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

    protected PackageService $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $user = auth()->user();
        $packages =$this->packageService
            ->byTenant($user->tenant?->id)
            ->get();

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
            $newPackage = $this->packageService->createNewPackage($validator['type'], $validator);

            $this->extracted($validator, $newPackage, $request);

            DB::commit();
            notify('Berhasil', 'Data paket berhasil dibuat!', 'success')->autoClose();

            return redirect()->back();
        } catch (Throwable $th) {
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
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
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.package.index'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = $this->getArr($request);

        DB::beginTransaction();
        try {
            $package = PlanPackage::query()->whereId($id)->first();

            $this->extracted($validator, $package, $request);

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
     * @param  int  $id
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
            notify('Opps!', $th->getMessage(), 'error');
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

    /**
     * @param array $validator
     * @param PlanPackage $newPackage
     * @param Request $request
     * @return void
     * @throws Throwable
     */
    public function extracted(array $validator, PlanPackage $newPackage, Request $request): void
    {
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
    }
}
