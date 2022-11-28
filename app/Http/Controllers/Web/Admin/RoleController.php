<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spatie\Role;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
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
     */
    public function datatable()
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
                    ->addColumn('actions', function ($role){
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
                throw $e;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws \Exception
     */
    public function index(): View
    {
        $user = auth()->user();
        $roles = Role::query()
            ->when($user->tenant_id === null, function (Builder $subQuery) {
                $subQuery->whereNull('tenant_id');
            })
            ->get()->unique('name');

        if (!$user->hasRole('super-administrator')){
            unset($this->columns);
            $this->columns = [
                ['data' => 'id'],
                ['data' => 'name'],
                ['data' => 'type'],
                ['data' => 'actions'],
            ];
        }

        $this->setData('roles', $roles);
        $this->setData('columns', $this->columns);
        return $this->view('pages.web.role.role-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create()
    {
        if (\request()->ajax()){
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
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string'],
            'type' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
        ]);

        try {

            /* begin:: start permission service */
            $user = auth()->user();
            $permissionService = new PermissionService($user->tenant_id ?? null);
            $permissionService
                ->createNewRole(collect($input)->forget('permissions')->toArray())
                ->syncPermissions($input['permissions']);
            /* end:: start permission service */

            notify('Berhasil!', 'Behasil membuat role baru', 'success');
            return redirect()->back();
        }catch (Throwable $e){
            notify('Oops!', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role)
    {
        try {
            if ($role->users_count > 0){
                throw new \Exception('Tidak dapat mengapus role, karena role ini sedang digunakan!', 500);
            }

            $role->delete();

            notify('Berhasil!', 'Berhasil menghapus role', 'success');
            return redirect()->back();
        }catch (Throwable $e){
            notify('Oops!', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
