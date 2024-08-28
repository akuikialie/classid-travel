<?php

/** @noinspection ALL */

namespace App\Http\Controllers\Web\Admin;

use App\Enums\PermissionType;
use App\Enums\RoleEnum;
use App\Enums\UserStatus;
use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;
use App\Models\Tenant\Tenant;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Exceptions\Exception;
use function response;

class RoleController extends Controller
{

    protected string $forPage = 'role';

    private array $columns = [
        ['data' => 'id'],
        ['data' => 'name'],
        ['data' => 'type'],
        ['data' => 'tenant'],
        ['data' => 'usages'],
        ['data' => 'actions'],
    ];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @return JsonResponse|void
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function datatable(Request $request)
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $roles = Role::query()
                    ->when(\request()->get('role_name'), function (Builder $subQuery) {
                        $subQuery->where('name', \request()->get('role_name'));
                    })
                    ->when(\request()->get('travel_name'), function (Builder $subQuery) {
                        $subQuery->where('tenant_id', Hashids::decode(\request()->get('travel_name')));
                    })
                    ->when(
                        !$user->hasRole('super-administrator') && isset($user->tenant_id),
                        function (Builder $subQuery) use ($user) {
                            $subQuery->where('tenant_id', $user->tenant_id);
                        }
                    )
                    // ->with(['permissions', 'users', 'tenant'])
                    ->with(['permissions', 'tenant'])
                    // ->withCount(['users'])
                    ->oldest('id');

                $datatable = datatables()->eloquent($roles)
                    ->filter(function (Builder $query) use ($request, $user) {
                        /* begin:: apply custom filter */
                        $customFilters = collect($request->input('filter'));
                        if ($customFilters->count() > 0) {
                            foreach ($customFilters as $filter) {

                                if ($filter['name'] == 'role') {
                                    $role = $filter['value'] ?? null;
                                    if ($role){
                                        $query->where('name' , $role);

                                    }
                                    continue;
                                }
                                if ($filter['name'] == 'tenant') {
                                    $tenant = $filter['value'] ?? null;
                                    if ($tenant){
                                        $query->whereHas('tenant', function (Builder $query) use ($tenant) {
                                            $query->where('name', $tenant);
                                        });
                                    }

                                    continue;
                                }

                                $query->where($filter['name'], $filter['value']);
                            }
                        }
                        /* end:: apply custom filter */

                        /* begin:: filter search */

                            $query->when($request->input('search')['value'], function (Builder $subQuery) use ($request, $user) {
                                $subQuery->where('tenant_id', $user->tenant_id)
                                    ->where(function ($subQuery) use ($request){
                                        $subQuery->orWhere('name', 'like', "%" . $request->input('search')['value'] . "%");
                                        $subQuery->orWhere('type', 'like', "%" . $request->input('search')['value'] . "%");
                                    });
                            });

                        /* end:: filter search */
                    })
                    ->addIndexColumn()
                    ->addColumn('name', function ($role) {
                        return $role->name;
                    })->addColumn('type', function ($role) {
                        return $role->type;
                    })->addColumn('usages', function ($role) {
                        $permissionGroups = collect($role->permissions)->groupBy('group');
                        $permissionsTotal = $permissionGroups->count();

                        // return '<div class="ms-5 text-center">
                        //     <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">' . $role->users_count . ' users</a>
                        //     <div class="fw-semibold text-muted">' . $permissionsTotal . ' Permmissions</div>
                        // </div>';
                        return '<div class="ms-5 text-center">
                            <div class="fw-semibold text-muted">' . $permissionsTotal . ' Permmissions</div>
                        </div>';
                    })
                    ->addColumn('actions', function ($role) {
                        $this->setData('role', $role);
                        return $this->view('pages.web.role.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status', 'usages']);

                if (is_null($user->tenant_id)) {
                    $datatable->addColumn('tenant', function ($role) {
                        if (is_null($role->tenant)) {
                            return '-';
                        }
                        return $role->tenant->name;
                    });
                }

                return $datatable->make(true);
            } catch (Exception | \Exception $e) {
                logError($e, title: 'role - datatable');
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
     * @return Factory|View
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    public function index()
    {
        $this->setPageTitle('Roles');
        $this->setBreadCrumb([
            ['title' => 'Roles', 'url' => route('admin.role.index')],
        ]);

        $user = auth()->user();
        $roles = Role::query();

        $roleFilters = collect($roles
            ->when(
                !$user->hasRole('super-administrator') && isset($user->tenant_id),
                function (Builder $subQuery) use ($user) {
                    $subQuery->where('tenant_id', $user->tenant_id ?? null);
                }
            )
            ->get())->unique('name');
        $tenantFilters = Tenant::query()
            ->when(
                !$user->hasRole('super-administrator') && isset($user->tenant_id),
                function (Builder $subQuery) use ($user) {
                    $subQuery->where('id', $user->tenant_id ?? null);
                }
            )
            ->select('id', 'name')
            ->get();

        /* try {
             $roles->when(\request()->get('role_name'), function (Builder $subQuery) {
                 $subQuery->where('name', \request()->get('role_name'));
             })
                 ->when(\request()->get('travel_name'), function (Builder $subQuery) {
                     $subQuery->where('tenant_id', Hashids::decode(\request()->get('travel_name')));
                 })
                 ->when(!$user->hasRole('super-administrator') && isset($user->tenant_id),
                     function (Builder $subQuery) use ($user) {
                         $subQuery->where('tenant_id', $user->tenant_id);
                     })
                 ->with(['permissions', 'users', 'tenant'])
                 ->withCount(['users'])
                 ->oldest('id');
         } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
             if (isDevelopmentMode()) {
                 throw $e;
             } else {
                 notify('Oops!', 'Terjadi kesalahan!', 'error');
             }
             return redirect()->back();
         }*/

        if (isset($user->tenant_id)) {
            unset($this->columns);
            $this->columns = [
                ['data' => 'id'],
                ['data' => 'name'],
                ['data' => 'type'],
                ['data' => 'usages'],
                ['data' => 'actions'],
            ];
        }
        $this->setData('columns', $this->columns);


        //        $this->setData('roles', $roles->paginate());
        $this->setData('role_filters', $roleFilters);
        $this->setData('tenant_filters', $tenantFilters);
        //        $this->setData('columns', $this->columns);
        return $this->view('pages.web.role.role-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function create()
    {
        if (\request()->ajax()) {
            $default = [
                'view',
                'create',
                'update',
                'delete',
                'export',
            ];

            $permissions = Permission::query()
                ->get();

            $permissionGroupedList = [];

            foreach ($permissions->unique('group') as $group) {
                foreach ($default as $permission) {
                    $isAccessExists = $permissions->where('group', '=', $group->group)
                        ->where('name', '=' , "{$permission} {$group->group}")
                        ->first();
                    if (!$isAccessExists){
                        continue;
                    }
                    $permissionGroupedList[$group->group][] = [
                        'name' => "{$permission}",
                        'is_active' => false,
                    ];
                }
            }

            $this->setData('permission_grouped', $permissionGroupedList);


            return response()->json([
                'view' => $this->view('pages.web.role.modals.modal-create-role')->render(),
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
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string'],
            'type' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            /* begin:: default type input */
            $input = array_merge($input, [
                'type' => PermissionType::tenant->keyValue(),
            ]);
            /* end:: default type input */

            /* begin:: start permission service */
            $user = auth()->user();
            $permissionService = new PermissionService($user->tenant_id ?? null);
            $permissionService
                ->createNewRole(collect($input)->forget('permissions')->toArray())
                ->syncPermissions($input['permissions']);
            /* end:: start permission service */

            notify('Berhasil!', 'Behasil membuat role baru', 'success');

            DB::commit();
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'role - store');
            if (isDevelopmentMode()) {
                throw $e;
            }
            $message = 'Terjadi kesalahan!';
            if ($e->getCode() >= 900){
                $message = $e->getMessage();
            }
            notify('Oops!', $message, 'error');
            return redirect()->back();
        }
    }

    /**
     * @throws Exception
     */
    public function datatableRoleUsers(Role $role, Request $request)
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $users = User::query()
                    ->with(['roles', 'tenant'])
                    ->when($user->hasRole('super-administrator'), function (Builder $subQuery){
                        $subQuery->with(['tenant']);
                    })
                    ->when($role->name == RoleEnum::Jamaah->keyValue(), function (Builder $subQuery) use ($user) {
                        if (!$user->hasRole('super-administrator')) {
                            $subQuery->where('tenant_id', $user->tenant_id);
                        }
                    })
                    ->role([$role->name])
                    ->where('is_super', false)
                    ->latest('id');

                $datatable = datatables()->eloquent($users)
                    ->filter(function (Builder $query) use ($request, $user) {
                        /* begin:: filter search */

                        $query->when($request->input('search')['value'], function (Builder $subQuery) use ($request, $user) {
                            $subQuery->where('tenant_id', $user->tenant_id)
                                ->where(function ($subQuery) use ($request){
                                    $subQuery->orWhere('name', 'like', "%" . $request->input('search')['value'] . "%");
                                });
                        });

                        /* end:: filter search */
                    })
                    ->addIndexColumn()
                    ->addColumn('status', function ($user) {
                        $status = UserStatus::tryFrom($user->status);
                        return "<span class=\"badge badge-light-{$status->color()} text-uppercase\">{$status->label()}</span>";
                    })->addColumn('last_login', function ($user) {
                        if (is_null($user->last_login_at)) {
                            return '-';
                        }
                        return carbon($user->last_login_at)->format('d M, Y H:i:s');
                    })
                    ->addColumn('actions', function ($user) {
                        $this->setData('user', $user);
                        $this->setData('type', 'staff');
                        return $this->view('pages.web.user.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status']);

                if ($user->hasRole('super-administrator')) {
                    $datatable->addColumn('tenant', function ($user) {
                        if (is_null($user->tenant)) {
                            return '-';
                        }
                        return $user->tenant->name;
                    });
                }

                $datatable->addColumn('tenant', function ($user) {
                    if (is_null($user->tenant)) {
                        return '-';
                    }
                    return $user->tenant->name;
                });

                return $datatable->make(true);
            } catch (Exception | NotFoundExceptionInterface | ContainerExceptionInterface $e) {
                logError($e, title: 'Role');

                if (isDevelopmentMode()) {
                    throw $e;
                } else {
                    throw new \Exception('Terjadi kesalahan!');
                }
            }
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return Factory|View
     * @throws \Exception
     */
    public function show(Role $role)
    {
        $this->setPageTitle('View Role Details');
        $this->setBreadCrumb([
            ['title' => 'Roles', 'url' => route('admin.role.index')],
            ['title' => 'View Role Details', 'url' => '#']
        ]);

        $user = auth()->user();
        $roles = Role::query()
            ->when($user->tenant_id === null, function (Builder $subQuery) {
                $subQuery->whereNull('tenant_id');
            })
            ->where('id', '!=', 1)
            ->get()->unique('name');

        $columns = [
            ['data' => 'id'],
            ['data' => 'name'],
            ['data' => 'tenant'],
            ['data' => 'status'],
            ['data' => 'last_login'],
            ['data' => 'actions'],
        ];

        $this->setData('columns', $columns);

        $this->setData('roles', $roles);
        $this->setData('type', 'staff');

        $this->setData('role', $role);
        return $this->view('pages.web.role.role-show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return JsonResponse
     * @throws \Exception
     */
    public function edit(Role $role)
    {
        if (\request()->ajax()) {

            $default = [
                'view',
                'create',
                'update',
                'delete',
                'export',
            ];

            $permissions = Permission::query()
                ->get();

            $permissionGroupedList = [];

            $rolePermissions = collect($role->permissions);

            foreach ($permissions->unique('group') as $group) {
                foreach ($default as $permission) {
                    $isAccessExists = $permissions->where('group', '=', $group->group)
                        ->where('name', '=' , "{$permission} {$group->group}")
                        ->first();
                    if (!$isAccessExists){
                        continue;
                    }

                    $isHasPermission = $rolePermissions->firstWhere('name', strtolower("{$permission} {$group->group}"));

                    $permissionGroupedList[$group->group][] = [
                        'name' => "{$permission}",
                        'is_active' => (bool)$isHasPermission,
                    ];
                }
            }

            $this->setData('permission_grouped', $permissionGroupedList);

            $this->setData('role', $role);
            return response()->json([
                'view' => $this->view('pages.web.role.modals.modal-edit-role')->render()
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request, Role $role)
    {
        $input = $request->validate([
            'name' => ['required', 'string'],
            'type' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            /* begin:: start permission service */
            $user = auth()->user();
            $permissionService = new PermissionService($user->tenant_id ?? null);
            $permissionService
                ->syncPermissions($input['permissions'] ?? [], modelClass: $role);
            /* end:: start permission service */

            notify('Berhasil!', 'Behasil memperbarui role', 'success');

            DB::commit();
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'role - update');
            if (isDevelopmentMode()) {
                throw $e;
            }
            $message = 'Terjadi kesalahan!';
            if ($e->getCode() >= 900){
                $message = $e->getMessage();
            }
            notify('Oops!', $message, 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Role $role)
    {
        try {
            if ($role->users_count > 0) {
                throw new \Exception('Tidak dapat mengapus role, karena role ini sedang digunakan!', 500);
            }

            $role->delete();

            notify('Berhasil!', 'Berhasil menghapus role', 'success');
            return redirect()->intended(route('admin.role.index'));
        } catch (Throwable $e) {
            logError($e, title: 'role - delete');
            if (isDevelopmentMode()) {
                throw $e;
            }
            $message = 'Terjadi kesalahan!';
            if ($e->getCode() > 900) {
                $message = $e->getMessage();
            }
            notify('Oops!', $message, 'error');

            return redirect()->back();
        }
    }
}
