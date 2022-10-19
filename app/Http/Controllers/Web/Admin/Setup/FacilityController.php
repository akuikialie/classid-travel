<?php

namespace App\Http\Controllers\Web\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Models\Plan\PlanFacility;
use App\Services\FacilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FacilityController extends Controller
{

    protected FacilityService $facilityService;

    public function __construct(FacilityService $facilityService)
    {
        $this->facilityService = $facilityService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facilities = PlanFacility::query()
            ->withCount(['media', 'packages'])
            ->get();

        return view('pages.web.setup.facility.facility-index', [
            'facilities' => $facilities,
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
                'view' => view('pages.web.setup.facility.modal.wizard-create-modal', [
                    'category_facilities' => $categoryFacilities,
                ])->render(),
            ]);
        }else{
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('setup.facility.index'));
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
            'name' => ['required', 'unique:facilities,name', 'string'],
            'type' => ['required', 'string'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $newFacility = $this->facilityService->createNewFacility($validator);

            if ($request->hasfile('photo_collection')) {
                $this->facilityService->AddImagesToFacility($newFacility, $request);
            }

            DB::commit();
            notify('Berhasil', 'Data fasilitas berhasil dibuat!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
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
        notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('setup.facility.index'));
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
                'view' => view('pages.web.setup.facility.modal.wizard-edit-modal', [
                    'facility' => $facility,
                    'category_facilities' => $categoryFacilities,
                ])->render(),
            ]);
        }else{
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('setup.facility.index'));
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
            'name' => ['required', 'unique:facilities,name', 'string'],
            'type' => ['required', 'string'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $facility = PlanFacility::query()->whereId($id)->first();

            if ($request->hasfile('photo_collection')) {
                $this->facilityService->AddImagesToFacility($facility, $request);
            }

            $facility->name = $validator['name'];
            $facility->type = $validator['type'];
            $facility->save();

            DB::commit();
            notify('Berhasil', 'Data fasilitas berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
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
            throw $th;
        }
    }
}
