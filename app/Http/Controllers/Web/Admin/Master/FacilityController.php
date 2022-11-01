<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Http\Controllers\Web\Admin\Controller;
use App\Models\Plan\PlanFacility;
use App\Services\FacilityService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FacilityController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $user = auth()->user();
        $facilities = PlanFacility::query()
            ->withCount(['media', 'packages'])
            ->tenantId($user->tenant_id)
            ->get();

        return view('pages.web.master.facility.facility-index', [
            'facilities' => $facilities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|RedirectResponse|Redirector|JsonResponse
     */
    public function create(): JsonResponse|Redirector|Application|RedirectResponse
    {
        if (request()->ajax()) {

            $categoryFacilities = [
                [
                    'name' => 'Perjalanan',
                    'icon' => 'bx bx-car bx-tada',
                ], [
                    'name' => 'Penginapan',
                    'icon' => 'bx bx-building-house bx-tada',
                ], [
                    'name' => 'Makan',
                    'icon' => 'bx bxs-bowl-hot bx-tada',
                ],
            ];

            return response()->json([
                'view' => view('pages.web.master.facility.modal.wizard-create-modal', [
                    'category_facilities' => $categoryFacilities,
                ])->render(),
            ]);
        }else{
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.facility.index'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = $request->validate([
            'name' => ['required', 'unique:facilities,name', 'string'],
            'type' => ['required', 'string'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $facilityService = new FacilityService($user->tenant_id);
            $facilityService->createFacility($validator)
                ->addGallery($request)
                ->get();

            DB::commit();
            notify('Berhasil', 'Data fasilitas berhasil dibuat!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $th) {
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
        return redirect(route('master.facility.index'));
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

            $facility = PlanFacility::query()->whereId($id)->first();

            $categoryFacilities = [
                [
                    'name' => 'Perjalanan',
                    'icon' => 'bx bx-car bx-tada',
                ], [
                    'name' => 'Penginapan',
                    'icon' => 'bx bx-building-house bx-tada',
                ], [
                    'name' => 'Makan',
                    'icon' => 'bx bxs-bowl-hot bx-tada',
                ],
            ];

            return response()->json([
                'view' => view('pages.web.master.facility.modal.wizard-edit-modal', [
                    'facility' => $facility,
                    'category_facilities' => $categoryFacilities,
                ])->render(),
            ]);
        }else{
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.facility.index'));
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
        $validator = $request->validate([
            'name' => ['required', 'unique:facilities,name', 'string'],
            'type' => ['required', 'string'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {

            $user = auth()->user();
            $facilityService = new FacilityService($user->tenant_id);
            $facilityService
                ->facilityId($id)
                ->addGallery($request)
                ->update($validator);

            DB::commit();
            notify('Berhasil', 'Data fasilitas berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $th) {
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
            $facility = PlanFacility::query()
                ->withCount(['packages'])
                ->whereId($id)->first();

            if ($facility->packages_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus fasilitas, karena fasilitas ini sedang digunakan!', 500);
            }
            $facility->delete();

            notify('Berhasil', 'Data fasilitas berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $th) {
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
