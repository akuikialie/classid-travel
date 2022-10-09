<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\Statuses;
use App\Enums\VirtualAccount;
use App\Http\Controllers\Controller;
use App\Jobs\Plan\Package\AddPackageToJamaah;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = PlanPackage::query()
            ->with(['myPlan:id,value'])
            ->where(function($subQuery){
                $subQuery->where('is_publish', true);
                $subQuery->where('status', Statuses::tryFrom('active')->keyValue());
            })
            ->select('id','plan_id','name', 'description', 'amount')
            ->get();
        // dd($packages);
        return view('pages.mobile.package.package-index', ['packages' => $packages]);
    }

    public function addPackageToJamaah($package_id)
    {
        DB::beginTransaction();
        try {
            $package = PlanPackage::query()->where('id', $package_id)->first();
            $jamaah = Jamaah::query()->where('user_id', auth()->user()->id)->first();

            /* add package to jamaah */
            $this->dispatch(new AddPackageToJamaah($package, $jamaah));

            DB::commit();
            return redirect(route('tabungan.index'));
        } catch (\Throwable $th) {
            DB::rollBack();
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
