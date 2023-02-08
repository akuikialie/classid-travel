<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Admin\Fragment\TenantFragmentController;
use App\Models\Tenant\Tenant;
use App\Services\TenantService;
use App\Traits\FragmentRenderer;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

class TenantController extends Controller
{
    use FragmentRenderer;

    protected string $forPage = 'travel';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setData('current_page', $this->forPage);
    }


    /**
     * @return JsonResponse|void
     * @throws \Yajra\DataTables\Exceptions\Exception
     * @throws Exception
     */
    public function datatable(Request $request)
    {
        if (\request()->ajax()) {
            try {
                $tenants = Tenant::query()
                    ->latest('id');
                return datatables()->eloquent($tenants)
                    ->filter(function (Builder $query) use ($request) {
                        /* begin:: apply custom filter */
                        $customFilters = collect($request->input('filter'));
                        if ($customFilters->count() > 0) {
                            foreach ($customFilters as $filter) {
                                if ($filter['value'] == 'all') continue;

                                if ($filter['name'] == 'status') {
                                    $status = $filter['value'] == 'active';
                                    $query->where('is_active', $status);
                                    continue;
                                }
                                $query->where($filter['name'], $filter['value']);
                            }
                        }
                        /* end:: apply custom filter */

                        /* begin:: filter search */
                        $query->when($request->input('search')['value'] && $customFilters->count() < 1, function (Builder $subQuery) use ($request) {
                            $subQuery->where('slug', 'like', "%" . $request->input('search')['value'] . "%");
                            $subQuery->orWhere('app_domain', 'like', "%" . $request->input('search')['value'] . "%");
                            $subQuery->orWhere('name', 'like', "%" . $request->input('search')['value'] . "%");
                            $subQuery->orWhere('BCN', 'like', "%" . $request->input('search')['value'] . "%");
                        });
                        /* end:: filter search */
                    })
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
                        }
                        return '<span class="badge badge-danger text-uppercase">inactive</span>';
                    })->addColumn('created_date', function ($row) {
                        return carbon($row->created_at)->format('d M, Y');
                    })->addColumn('actions', function ($row) {
                        $this->setData('tenant', $row);
                        return $this->view('pages.web.tenant.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status'])
                    ->make(true);
            } catch (\Yajra\DataTables\Exceptions\Exception $e) {
                logError($e, title: 'Tenant');
                if (isDevelopmentMode()) {
                    throw $e;
                }
                throw new Exception('Terjadi kesalahan!.');
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
        $this->setPageTitle('Travel');
        $this->setBreadCrumb('Travel');

        return $this->view('pages.web.tenant.tenant-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(): JsonResponse
    {
        if (\request()->ajax()) {
            $lastBCN = Tenant::query()->max('BCN');
            setDefaultRequest('BCN', $lastBCN + 1);
            return \response()->json([
                'view' => $this->view('pages.web.tenant.modals.modal-create-tenant')->render(),
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
        $input = $request->validate([
            'BCN' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'app_domain' => ['required', 'string', 'unique:tenants,app_domain'],
            'phone' => ['required', 'numeric'],
        ]);

        DB::beginTransaction();
        try {
            $input = array_merge($input, [
                'slug' => $input['name'],
            ]);

            (new TenantService())
                ->createNewtenant($input);
            DB::commit();

            notify('Berhasil', 'Berhasil membuat akun travel baru', 'success');
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Tenant');
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
     * @param Tenant|null $tenant
     * @param string|null $slug
     * @return View
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function show(?Tenant $tenant = null, ?string $slug = null): View
    {
        $this->setPageTitle('Profil Travel');
        $this->setBreadCrumb('Profil Travel');
        try {
            $user = auth()->user();
            if (!($user->tenant_id ?? null)) {
                abort(404);
            }

            if (is_null($tenant)) {
                $tenant = Tenant::query()
                    ->with(['media'])
                    ->whereId($user->tenant_id)
                    ->first();
            }

            if (\request()->has('fragment')) {
                $fragmentName = \request()->get('fragment');
                $fragmentParameter = \request()->get('parameter');
                $this->addGlobalParams('fragment_active', $fragmentName);
                $this->fragment(new TenantFragmentController())
                    ->render($fragmentName ?? 'target', [
                        'tenant' => $tenant,
                        'parameter' => $fragmentParameter ?? null,
                  ]);
            }

            $this->setData('tenant', $tenant);
        } catch (Exception $e) {
            logError($e, title: 'Tenant');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
        }

        return $this->view('pages.web.tenant.tenant-show');
    }

    /**
     * @param string $slug
     * @return Factory|View
     * @throws ReflectionException
     */
    public function showProfile(string $slug)
    {
        $this->setPageTitle('Profil Travel');
        $this->setBreadCrumb('Profil Travel');

        try {
            $user = auth()->user();
            if (!($user->tenant_id ?? null)) {
                abort(404);
            }

            $tenant = Tenant::query()
                ->with(['media'])
                ->whereId($user->tenant_id)
                ->first();

            $this->addGlobalParams('fragment_active', $slug);

            $this->fragment(new TenantFragmentController())
                ->render($slug ?? 'overview', [
                    'tenant' => $tenant,
                    'parameter' => $fragmentParameter ?? null,
                ]);

            $this->setData('tenant', $tenant);
        } catch (Exception $e) {
            logError($e, title: 'Tenant');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
        }

        return $this->view('pages.web.tenant.tenant-show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tenant $tenant
     * @return JsonResponse
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
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tenant|null $tenant
     * @return RedirectResponse
     * @throws Throwable
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(Request $request, ?Tenant $tenant = null)
    {
        $user = auth()->user();

        $input = $request->validate([
            'avatar_remove' => ['nullable', 'string'],
            'name' => [Rule::requiredIf($user->tenant_id !== null), 'string'],
            'slug' => [Rule::requiredIf($user->tenant_id !== null), 'string'],
            'BCN' => [Rule::requiredIf($user->tenant_id === null), 'numeric'],
            'app_domain' => [Rule::requiredIf($user->tenant_id === null), 'string'],
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
                ->setTenant($tenant);

            if (isset($input['avatar_remove'])) {
                $tenantService->unsetAvatar();
            }
            $tenantService
                ->setAvatar($request)
                ->update($input, $user);
            /* end:: tenant service */

            DB::commit();

            notify('Berhasil', 'Data travel berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Tenant');
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
     * @param Tenant $tenant
     * @return RedirectResponse
     */
    public function destroy(Tenant $tenant)
    {
        try {
            $tenant->delete();

            notify('Berhasil', 'Travel berhasil di hapus', 'success')->autoClose();
            return redirect()->back();

        }catch (Throwable $e){
            return redirect()->back();
        }
    }

    /**
     * @throws Throwable
     */
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
                ->setTenant($tenant)
                ->addMediaCollection($request, $request->collection);
            /* end:: tenant service */

            notify('Berhasil!', "Berhasil memperbarui koleksi {$request->collection}", 'success');

            DB::commit();
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Tenant');
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
     * @param Tenant $tenant
     * @return RedirectResponse
     * @throws Throwable
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
                    ->setTenant($tenant)
                    ->setStatus($request->get('status'));
                notify('Berhasil!', "Status telah berubah", 'success');
                DB::commit();
            } else {
                throw new InvalidArgumentException('Tidak ada yang berubah!');
            }
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'Tenant');
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
