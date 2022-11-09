<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Yajra\DataTables\Exceptions\Exception;

class UserController extends Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function datatable()
    {
        if (\request()->ajax()) {
            $user = auth()->user();
            $users = User::query()
                ->when($user->hasRole(RoleEnum::SuperAdministrator->keyValue()), function (Builder $subQuery) use ($user){
                    $subQuery->where('id', '!=', $user->id);
                }, function (Builder $subQuery) use ($user){
                    $subQuery->where('id', '!=', $user->id)
                    ->where('is_super', '!=', true);
                })
                ->latest('id')
                ->get();
            try {
                return datatables()->of($users)
                    ->addIndexColumn()
                    ->addColumn('role', function ($user){
                        return $user->roles->first();
                    })
                    ->addColumn('actions', function ($user){
                        $this->setData('suser', $user);
                        return $this->view('pages.web.tenant.action.action-datatable');
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return $this->view('pages.web.user.user-index');
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
     * @return Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
