<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Admin\Fragment\TenantFragmentController;
use App\Models\Tenant\Tenant;
use App\Services\TenantService;
use App\Services\UserService;
use App\Traits\FragmentRenderer;
use App\Traits\ViewSupport;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Throwable;

class TenantController extends Controller
{

    use ViewSupport, FragmentRenderer;

    /**
     * @throws \Yajra\DataTables\Exceptions\Exception
     * @throws Exception
     */
    public function datatable()
    {
        if (\request()->ajax()) {
            $tenants = Tenant::query()
                ->latest('id')
                ->get();
            try {
                return datatables()->of($tenants)
                    ->addIndexColumn()
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })->addColumn('slug', function ($row) {
                        return $row->slug;
                    })->addColumn('app_domain', function ($row) {
                        return $row->app_domain;
                    })->addColumn('BCN', function ($row) {
                        return $row->BCN;
                    })->addColumn('BCN', function ($row) {
                        return $row->BCN;
                    })->addColumn('status', function ($row) {
                        if ($row->is_active) {
                            return '<span class="badge badge-success text-uppercase">active</span>';
                        } else {
                            return '<span class="badge badge-danger text-uppercase">inactive</span>';
                        }
                    })->addColumn('created_date', function ($row) {
                        return carbon($row->created_at)->format('d M, Y');
                    })->addColumn('actions', function ($row){
                        $this->setData('tenant', $row);
                        return $this->view('pages.web.tenant.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status'])
                    ->make(true);
            } catch (\Yajra\DataTables\Exceptions\Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return $this->view('pages.web.tenant.tenant-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|JsonResponse|RedirectResponse|Redirector
     * @throws Throwable
     */
    public function create()
    {
        if (\request()->ajax()) {
            $lastBCN = Tenant::query()->max('BCN');
            setDefaultRequest('BCN', $lastBCN + 1);
            return \response()->json([
                'view' => $this->view('pages.web.tenant.modals.modal-create-tenant')->render(),
            ]);
        }else {
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.destination.index'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'BCN' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'app_domain' => ['required', 'string', 'unique:tenants,app_domain'],
            'phone' => ['required', 'numeric'],
        ]);

        DB::beginTransaction();
        try {
            /* begin:: create new tenant */
            $input = array_merge($input, [
                'slug' => $input['name'],
            ]);

            if (str_contains($input['app_domain'], ' ')) {
                $input = array_merge($input, [
                    'app_domain' => Str::lower(str_replace(' ', '.', $input['app_domain']))
                ]);
            }

            $validAppDomain = dns_get_record($input['app_domain']);
            if (!is_array($validAppDomain) || count($validAppDomain) < 1) {
                throw new Exception('App domain tidak tersedia');
            }
            $newTenant = Tenant::query()->create($input);
            /* end:: create new tenant */

            /* begin:: user service -- create admin account + set is super == true (fix) */
            $userService = new UserService(tenantId: $newTenant->id);
            $userService->createNewUser([
                'name' => $input['name'],
                'phone' => $input['phone'],
                'password' => 'admin',
            ], false)
                ->setRole('administrator')
                ->setIsSuper(true);
            /* end:: user service -- create admin account + set is super == true (fix) */

            DB::commit();

            notify('Berhasil', 'Berhasil membuat akun travel baru', 'success');
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            notify('Oops', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Tenant|null $tenant
     * @return View
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function show(?Tenant $tenant = null): View
    {
        if (is_null($tenant)) {
            $user = auth()->user();
            $tenant = Tenant::query()
                ->with(['media'])
                ->whereId($user->tenant_id)
                ->first();
        }

        if (\request()->has('fragment')) {
            try {
                $fragmentName = \request()->get('fragment');
                $fragmentParameter = \request()->get('parameter');
                $this->setGlobalParams('fragment_active', $fragmentName);
                $this->fragment(new TenantFragmentController())
                    ->render($fragmentName ?? 'target', [
                        'tenant' => $tenant,
                        'parameter' => $fragmentParameter ?? null,
                    ]);
            } catch (ReflectionException $e) {
                notify('Oops!', $e->getMessage(), 'error');
            }
        }

        try {
            $this->setData('tenant', $tenant);
        } catch (Exception $e) {
            notify('Oops!', $e->getMessage(), 'error');
        }

        return $this->view('pages.web.tenant.tenant-show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tenant $tenant
     * @return Application|JsonResponse|RedirectResponse|Redirector
     * @throws Throwable
     */
    public function edit(Tenant $tenant)
    {
        if (\request()->ajax()) {
            setDefaultRequest([
                'name' => $tenant->name,
                'BCN' => $tenant->BCN,
                'app_domain' => $tenant->app_domain,
                'tenant_hash' => $tenant->hash,
            ]);
            return \response()->json([
                'view' => $this->view('pages.web.tenant.modals.modal-edit-tenant')->render(),
            ]);
        }else {
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.destination.index'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tenant|null $tenant
     * @return RedirectResponse
     */
    public function update(Request $request, ?Tenant $tenant = null)
    {
        $user = auth()->user();

        $input = $request->validate([
            'avatar_remove' => ['nullable', 'string'],
            'name' => [Rule::requiredIf($user->hasRole(RoleEnum::Admin->keyValue())), 'string'],
            'slug' => [Rule::requiredIf($user->hasRole(RoleEnum::Admin->keyValue())), 'string'],
            'BCN' => [Rule::requiredIf($user->hasRole(RoleEnum::SuperAdministrator->keyValue())), 'numeric'],
            'app_domain' => [Rule::requiredIf($user->hasRole(RoleEnum::SuperAdministrator->keyValue())), 'string'],
        ]);

        DB::beginTransaction();
        try {
            /* begin:: tenant service */

            if (is_null($tenant)) {
                $tenant = Tenant::query()
                    ->with(['media'])
                    ->whereId($user->tenant_id)
                    ->first();
            }

            $tenantService = new TenantService($user->tenant_id ?? $tenant->id);
            $tenantService
                ->tenantId($tenant->id);

            if (isset($input['avatar_remove'])) {
                $tenantService->unsetAvatar();
            } else {
                $tenantService
                    ->setAvatar($request);
            }
            $tenantService->update($input, $user);
            /* end:: tenant service */

            DB::commit();

            notify('Berhasil', 'Data travel berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();

        } catch (Throwable $e) {

            DB::rollBack();
            notify('Oops!', $e->getMessage(), 'error');

            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tenant $tenant
     * @return Response
     */
    public function destroy(Tenant $tenant)
    {
        dd($tenant);
    }

    public function addMedia(Request $request, ?Tenant $tenant = null)
    {
        $request->validate([
            'collection' => ['required', 'string'],
            'collections' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            if (is_null($tenant)) {
                $user = auth()->user();
                $tenant = Tenant::query()
                    ->with(['media'])
                    ->whereId($user->tenant_id)
                    ->first();
            }

            /* begin:: tenant service */
            $tenantService = new TenantService($tenant->id);
            $tenantService
                ->tenantId($tenant->id)
                ->addMediaCollection($request, $request->collection);
            /* end:: tenant service */

            notify('Berhasil!', "Berhasil memperbarui koleksi {$request->collection}", 'success');

            DB::commit();
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            notify('Oops!', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @param Tenant $tenant
     * @return RedirectResponse
     */
    public function changeStatus(Request $request, Tenant $tenant)
    {
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        /* begin:: start tenant service */
        try {
            $tenantService = new TenantService(tenantId: $tenant->id);
            if ($request->has('status')) {
                $tenantService
                    ->tenantId($tenant->id)
                    ->setStatus($request->get('status'));
                notify('Berhasil!', "Status telah berubah", 'success');
                DB::commit();
            }else{
                throw new InvalidArgumentException('Tidak ada yang berubah!');
            }


            return redirect()->back();
        }catch (Throwable $e){
            notify('Oops!', $e->getMessage(), 'error');
            return redirect()->back();
        }
        /* end:: start tenant service */
    }
}
