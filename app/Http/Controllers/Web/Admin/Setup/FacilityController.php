<?php

namespace App\Http\Controllers\Web\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Jobs\Plan\Facility\AddImagesToFacility;
use App\Jobs\Plan\Facility\CreateNewFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.web.setup.facility.facility-index');
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
                'view' => view('pages.web.setup.facility.modal.wizard-setup-modal', [
                    'category_facilities' => $categoryFacilities,
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
            'name' => ['required', 'unique:facilities,name', 'string'],
            'type' => ['required', 'string'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $createNewFacility = new CreateNewFacility($validator);
            $newFacility = $createNewFacility->handle();
            if ($request->hasfile('photo_collection')) {
                $addFile = new AddImagesToFacility($newFacility, $request);
                $addFile->handle();
            }

            DB::commit();
            return redirect()->back()->with('success', 'work');
        } catch (\Throwable $th) {
            DB::beginTransaction();
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
