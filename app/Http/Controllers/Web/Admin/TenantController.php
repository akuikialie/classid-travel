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
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
     * @param $hash
     * @return Application|Factory|View
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function show($hash)
    {
        $tenant = Tenant::query()
            ->with('media')
            ->byHashOrFail($hash);

        if (\request()->has('fragment')){
            try {
                $fragmentName = \request()->get('fragment');
                $fragmentParameter = \request()->get('parameter');
                $this->setGlobalParams('fragment_active', $fragmentName);
                $this->fragment(new TenantFragmentController())
                    ->render($fragmentName ?? 'target', [
                        'tenant' => $tenant,
                        'parameter' => $fragmentParameter ?? null,
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
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function update(Request $request, $hash)
    {
        $input = $request->validate([
            'avatar_remove' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            /* begin:: tenant service */
            $tenant = Tenant::query()
                ->byHashOrFail($hash);

            $user = auth()->user();
            $tenantService = new TenantService($user->tenant_id);
            $tenantService
                ->tenantId($tenant->id);

            if ($input['avatar_remove']){
                $tenantService->unsetAvatar();
            }else{
                $tenantService
                    ->setAvatar($request);
            }
            $tenantService->update($input);
            /* end:: tenant service */

            DB::commit();

            notify('Berhasil', 'Data paket berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();

        }catch (\Throwable $e){

            DB::rollBack();
            notify('Oops!', $e->getMessage(), 'error');

            return redirect()->back();
        }

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

    public function addMedia(Request $request, $hash)
    {
        $request->validate([
            'collection' => ['required', 'string'],
            'collections' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {

            /* begin:: tenant service */
            $tenant = Tenant::query()
                ->byHashOrFail($hash);

            $tenantService = new TenantService($tenant->id);
            $tenantService
                ->tenantId($tenant->id)
                ->addMediaCollection($request, $request->collection);
            /* end:: tenant service */

            notify('Berhasil!', "Berhasil memperbarui koleksi {$request->collection}", 'success');

            DB::commit();
            return redirect()->back();
        }catch (\Throwable $e){
            DB::rollBack();
            notify('Oops!', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
