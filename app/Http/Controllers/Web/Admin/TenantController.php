<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Admin\Fragment\TenantFragmentController;
use App\Models\Tenant\Tenant;
use App\Services\TenantService;
use App\Traits\FragmentRenderer;
use App\Traits\ViewSupport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TenantController extends Controller
{

    use ViewSupport,FragmentRenderer;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $tenant = Tenant::query()
            ->whereId($id)->first();

        if (\request()->has('fragment')){
            try {
                $fragmentName = \request()->get('fragment');
                $this->setGlobalParams('fragment_active', $fragmentName);
                $this->fragment(new TenantFragmentController())
                    ->render($fragmentName ?? 'target', [
                        'hash' => $tenant->hash,
                    ]);
            } catch (\ReflectionException $e) {
            }
        }
        return view('pages.web.tenant.tenant-show', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $hash)
    {
        $input = $request->validate([
            'avatar_remove' => ['required', 'string'],
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
        ]);

        try {
            /* begin:: tenant service */
            $tenant = Tenant::query()
                ->byHashOrFail($hash);

            $user = auth()->user();
            $tenantService = new TenantService($user->tenant_id);
            $tenantService
                ->
                ->setAvatar($request);
            /* end:: tenant service */
        }catch (\Throwable $e){
            return redirect()->back();
        }


        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
