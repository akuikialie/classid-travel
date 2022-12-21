<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\PermissionType;
use App\Http\Controllers\Controller;
use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;
use App\Models\Tenant\Tenant;
use App\Services\PermissionService;
use DB;
use Hashids;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class RoleController extends Controller
{

    protected string $forPage = 'role';

    private array $columns = [
        ['data' => 'id'],
        ['data' => 'name'],
        ['data' => 'type'],
        ['data' => 'tenant'],
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
     * @throws Exception
     * @throws \Exception
     */
    public function datatable(Role $role)
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $roles = Role::query()
                    ->when($user->tenant_id !== null, function (Builder $subQuery) use ($user) {
                        $subQuery->where('tenant_id', $user->tenant_id);
                    })
                    ->with(['permissions', 'users', 'tenant'])
                    ->latest('id')
                    ->get();

                $datatable = datatables()->of($roles)
                    ->addIndexColumn()
                    ->addColumn('name', function ($role) {
                        return $role->name;
                    })->addColumn('type', function ($role) {
                        return $role->type;
                    })
                    ->addColumn('actions', function ($role) {
                        $this->setData('role', $role);
                        return $this->view('pages.web.role.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status']);

                if ($user->hasRole('super-administrator')) {
                    $datatable->addColumn('tenant', function ($role) {
                        if (is_null($role->tenant)) {
                            return '-';
                        }
                        return $role->tenant->name;
                    });
                }

                return $datatable->make(true);
            } catch (Exception|\Exception $e) {
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
     * @return \Illuminate\Contracts\View\View|Factory
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    public function index(): Factory|\Illuminate\Contracts\View\View
    {
        $user = auth()->user();
        $roles = Role::query();

        $roleFilters = collect($roles
            ->when(!$user->hasRole('super-administrator') && isset($user->tenant_id),
                function (Builder $subQuery) use ($user) {
                    $subQuery->where('tenant_id', $user->tenant_id ?? null);
                })
            ->get())->unique('name');
        $tenantFilters = Tenant::query()
            ->when(!$user->hasRole('super-administrator') && isset($user->tenant_id),
                function (Builder $subQuery) use ($user) {
                    $subQuery->where('id', $user->tenant_id ?? null);
                })
            ->select('id', 'name')
            ->get();

        try {
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
        }

        $this->setData('roles', $roles->paginate());
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
            ];

            $permissions = Permission::query()
                ->get()
                ->unique('group');

            $permissionGroupedList = [];

            foreach ($permissions as $group) {
                foreach ($default as $permission) {
                    $permissionGroupedList[$group->group][] = [
                        'name' => "{$permission}",
                        'is_active' => false,
                    ];
                }
            }

            $this->setData('permission_grouped', $permissionGroupedList);


            return \response()->json([
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
     * @param Role $role
     * @return Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function show(Role $role)
    {
        $user = auth()->user();
        $roles = Role::query()
            ->when($user->tenant_id === null, function (Builder $subQuery) {
                $subQuery->whereNull('tenant_id');
            })
            ->where('id', '!=', 1)
            ->get()->unique('name');

        $userIds = collect($role->users)->pluck('id');

        $this->setData('roles', $roles);
        $this->setData('search_users', $userIds);

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
            ];

            $permissions = Permission::query()
                ->get()
                ->unique('group');

            $permissionGroupedList = [];

            $rolePermissions = collect($role->permissions);

            foreach ($permissions as $group) {
                foreach ($default as $permission) {
                    $isHasPermission = $rolePermissions->firstWhere('name', strtolower("{$permission} {$group->group}"));

                    $permissionGroupedList[$group->group][] = [
                        'name' => "{$permission}",
                        'is_active' => (bool)$isHasPermission,
                    ];
                }
            }

            $this->setData('permission_grouped', $permissionGroupedList);

            $this->setData('role', $role);
            return \response()->json([
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
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }
}
