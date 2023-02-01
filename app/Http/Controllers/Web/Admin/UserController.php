<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\PermissionType;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Spatie\Role;
use App\Models\User;
use App\Services\UserService;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class UserController extends Controller
{

    protected string $forPage = 'user';

    private array $columns = [
        ['data' => 'id'],
        ['data' => 'name'],
        ['data' => 'role'],
        ['data' => 'tenant'],
        ['data' => 'status'],
        ['data' => 'last_login'],
        ['data' => 'actions'],
    ];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @return JsonResponse|void
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    public function datatable(Request $request, ?string $type = null)
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $users = User::query()
                    ->with(['roles'])
                    ->when(is_null($user->tenant_id), function (Builder $subQuery) use ($user) {
                        $subQuery->with(['tenant']);
                    }, function (Builder $subQuery) use ($user) {
                        if (!is_null($user->tenant_id)){
                            $subQuery->where('tenant_id', $user->tenant_id);
                            $subQuery->where('id', '!=', $user->id);
                        }
                    })
                    ->when($type == 'calon-jamaah', function (Builder $subQuery) {
                        $subQuery->role(['jamaah']);
                    }, function (Builder $subQuery) {
                        $subQuery->whereHas('roles', function (Builder $subQuery) {
                            $subQuery->where('name', '!=', 'jamaah');
                        });
                    })
                    ->where('is_super', false)
                    ->latest('id');

                $datatable = datatables()->eloquent($users)
                ->filter(function (Builder $query) use ($request) {
                    /* begin:: apply custom filter */
                    $customFilters = collect($request->input('filter'));
                    if ($customFilters->count() > 0) {
                        foreach ($customFilters as $filter) {
                            if ($filter['name'] == 'role') {
                                $role = $filter['value'] ?? null;
                                if ($role) {
                                    $query->whereHas('roles', function (Builder $subQuery) use($role){
                                       $subQuery->where('name', $role);
                                    });
                                }
                                continue;
                            }
                            $query->where($filter['name'], $filter['value']);
                        }
                    }
                    /* end:: apply custom filter */

                    /* begin:: filter search */
                    $query->when($request->input('search')['value'], function (Builder $subQuery) use ($request) {
                        $subQuery->where('name', 'like', "%" . $request->input('search')['value'] . "%");
                    });
                    /* end:: filter search */
                })
                    ->addIndexColumn()
                    ->addColumn('role', function ($user) {
                        return $user->roles->pluck('name')->first();
                    })->addColumn('status', function ($user) {
                        $status = UserStatus::tryFrom($user->status);
                        return "<span class=\"badge badge-light-{$status->color()} text-uppercase\">{$status->label()}</span>";
                    })->addColumn('last_login', function ($user) {
                        if (is_null($user->last_login_at)) {
                            return '-';
                        }
                        return carbon($user->last_login_at)->format('d M, Y H:i:s');
                    })
                    ->addColumn('actions', function ($user) use ($type) {
                        $this->setData('user', $user);
                        $this->setData('type', $type);
                        return $this->view('pages.web.user.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status']);

                if (is_null($user->tenant_id)) {
                    $datatable->addColumn('tenant', function ($user) {
                        if (is_null($user->tenant)) {
                            return '-';
                        }
                        return $user->tenant->name;
                    });
                }

                return $datatable->make(true);
            } catch (Exception | NotFoundExceptionInterface | ContainerExceptionInterface $e) {
                logError($e, title: 'User');
                if (isDevelopmentMode()) {
                    throw $e;
                }
                throw new \Exception('Terjadi kesalahan!');

            }
        }
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(?string $type = null)
    {
        if (is_null($type)) {
            return redirect()->route('admin.user.index', 'staff');
        }
        $this->setPageTitle(ucwords($type));
        $this->setBreadCrumb(ucwords($type));

        $user = auth()->user();
        $roles = Role::query()
            ->when(!$user->hasRole('super-administrator') && isset($user->tenant_id),
                function (Builder $subQuery) use ($user) {
                    $subQuery->where('tenant_id', $user->tenant_id ?? null);
                })
            ->when($user->tenant_id, function (Builder $query) use ($user) {
                $query->where('tenant_id', $user->tenant_id);
            })
            ->get()->unique('name');

        if (isset($user->tenant_id)) {
            unset($this->columns);
            $this->columns = [
                ['data' => 'id'],
                ['data' => 'name'],
                ['data' => 'role'],
                ['data' => 'status'],
                ['data' => 'last_login'],
                ['data' => 'actions'],
            ];
        }

        $this->setData('columns', $this->columns);

        $this->setData('type', $type);
        $this->setData('roles', $roles);
        return $this->view('pages.web.user.user-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(?string $type = null)
    {
        if (\request()->ajax()) {
            $user = auth()->user();
            $roles = Role::query()
                ->when($user->tenant_id === null, function (Builder $subQuery) {
                    $subQuery->whereNull('tenant_id');
                })
                ->when(!$user->hasRole('super-administrator'), function (Builder $subQuery) {
                    $subQuery->where('name', '!=', 'super-administrator');
                })
                ->where([
                    'type' => PermissionType::tenant->keyValue()
                ])
                ->get()->unique('name');

            $this->setData('roles', $roles);
            return \response()->json([
                'view' => $this->view('pages.web.user.modals.modal-add-admin')->render(),
            ]);
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param string|null $type
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request, ?string $type = null)
    {
        $input = $request->validate([
            'phone' => ['required', 'numeric'],
            'role' => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            /* begin: user service */
            $user = auth()->user();
            $userService = new UserService(tenantId: $user->tenant_id ?? null);
            $userService->createNewUser([
                'name' => "{$input['role']} - {$user->id}",
                'phone' => $input['phone'],
                'password' => 'admin',
            ], false)
                ->setRole($input['role'])
                ->setIsSuper(); // except from tenant, always set to false
            /* end: user service */

            DB::commit();
            notify('Berhasil', 'Berhasil menambahkan admin baru', 'success');
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'User');
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
     * @param string|null $type
     * @param User $user
     * @return void
     */
    public function show(User $user, ?string $type = null )
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string|null $type
     * @param User $user
     * @return void
     */
    public function edit( User $user, ?string $type = null)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string|null $type
     * @param User $user
     * @return Response
     */
    public function update(Request $request, User $user, ?string $type = null)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string|null $type
     * @param User $user
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(User $user, ?string $type = null)
    {
        try {
            $authUser = auth()->user();
            if ($authUser->is_super !== true) {
                if (
                    $user->is_super === true &&
                    $authUser->is_super === false
                ) {
                    throw UnauthorizedException::forPermissions($user->roles->toArray());
                }
            }
            $user->delete();
            notify('Behasil!', 'Berhasil menghapus akun!', 'success');
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'User');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function changeStatus(Request $request, User $user, ?string $type = null)
    {
        $request->validate([
            'status' => ['required'],
        ]);

        try {
            /* begin:: start user service */
            $userService = new UserService($user->tenant_id ?? null);
            $userService
                ->setUser($user)
                ->setStatus(\request()->get('status'));
            /* end:: start user service */

            notify('Behasil!', 'Berhasil memperbarui status akun!', 'success');
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'User');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }
}
