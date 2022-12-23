<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Enums\FacilityType;
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
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class FacilityController extends Controller
{

    protected string $forPage = 'facility';

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @return JsonResponse|void
     * @throws Exception
     * @throws Throwable
     */
    public function datatable()
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $facilities = PlanFacility::query()
                    ->withCount(['media', 'packages'])
                    ->tenantId($user->tenant_id)
                    ->get();

                $datatable = datatables()->of($facilities)
                    ->addIndexColumn()
                    ->addColumn('name', function ($model) {
                        return $model->name;
                    })->addColumn('type', function ($model) {
                        return ($model->type ?? '-');
                    })->addColumn('status', function ($model) {
                        if ($model->is_active) {
                            return '<span class="badge badge-success text-uppercase">active</span>';
                        } else {
                            return '<span class="badge badge-danger text-uppercase">inactive</span>';
                        }
                    })
                    ->addColumn('actions', function ($model) {
                        $this->setData('facility', $model);
                        return $this->view('pages.web.master.facility.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status' ]);

                return $datatable->make(true);
            } catch (Throwable $e) {
                logError($e, title: 'Facility');
                if (isDevelopmentMode()) {
                    throw $e;
                } else {
                    throw new \Exception('Terjadi kesalahan!');
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
        $this->setPageTitle('Fasilitas');
        $this->setBreadCrumb('Fasilitas');
        return $this->view('pages.web.master.facility.facility-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|RedirectResponse|Redirector|JsonResponse
     */
    public function create(): JsonResponse|Redirector|Application|RedirectResponse
    {
        if (request()->ajax()) {

            return response()->json([
                'view' => view('pages.web.master.facility.modal.wizard-create-modal', [
                    'category_facilities' => FacilityType::cases(),
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
        } catch (\Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Facility');
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
     * @param PlanFacility $planFacility
     * @return Application|Redirector|RedirectResponse
     */
    public function show(PlanFacility $planFacility): Redirector|RedirectResponse|Application
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PlanFacility $facility
     * @return Application|JsonResponse|Redirector|RedirectResponse
     * @throws \Exception|Throwable
     */
    public function edit(PlanFacility $facility): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (request()->ajax()) {

            setDefaultRequest([
                'name' => $facility->name,
                'type' => $facility->type,
            ]);

            $this->setData('category_facilities', FacilityType::cases());
            $this->setData('facility', $facility);

            return response()->json([
                'view' => $this->view('pages.web.master.facility.modal.wizard-edit-modal')->render(),
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PlanFacility $facility
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request, PlanFacility $facility): RedirectResponse
    {
        $validator = $request->validate([
            'name' => ['required', 'unique:facilities,name,'.$facility->id, 'string'],
            'type' => ['required', 'string'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {

            $user = auth()->user();
            $facilityService = new FacilityService($user->tenant_id);
            $facilityService
                ->setPlanFacility($facility)
                ->addGallery($request)
                ->update($validator);

            DB::commit();
            notify('Berhasil', 'Data fasilitas berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Facility');
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
     * @param PlanFacility $facility
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(PlanFacility $facility): RedirectResponse
    {
        try {
            if ($facility->packages_count > 0) {
                throw new \Exception('Tidak dapat mengapus fasilitas, karena fasilitas ini sedang digunakan!', 500);
            }
            $facility->delete();

            notify('Berhasil', 'Data fasilitas berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $e) {
            logError($e, title: 'Facility');
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
     * @param PlanFacility $facility
     * @return RedirectResponse
     * @throws Throwable
     */
    public function changeStatus(Request $request, PlanFacility $facility)
    {
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        /* begin:: start tenant service */
        try {
            $user = auth()->user();
            $destinationService = new FacilityService($user->tenant_id);
            if ($request->has('status')) {
                $destinationService
                    ->setPlanFacility($facility)
                    ->setStatus($request->get('status'));
                notify('Berhasil!', "Status telah berubah", 'success');
            }else{
                throw new InvalidArgumentException('Tidak ada yang berubah!');
            }
            return redirect()->back();
        }catch (Throwable $e){
            logError($e, title: 'Facility');
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
