<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Tenant\Tenant;
use App\Models\Transaction\Transaction;
use App\Traits\FragmentRenderer;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends Controller
{
    use FragmentRenderer;

    protected string $forPage = 'transaction';

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
            $user = auth()->user();
            try {
                $transactions = Transaction::query()
                    ->where('tenant_id', '=', $user->tenant_id)
                    ->with(['invocation', 'user'])
                    ->latest('id');
                return datatables()->eloquent($transactions)
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
                            $subQuery->orWhere('bcn', 'like', "%" . $request->input('search')['value'] . "%");
                        });
                        /* end:: filter search */
                    })
                    ->addIndexColumn()
                    ->addColumn('owner', function ($row) {
                        return "{$row->user->name} | {$row->user->phone}" ;
                    })->addColumn('virtual_account', function ($row) {
                        return $row->invocation->virtual_account;
                    })
                    ->addColumn('invoice_number', function ($row) {
                        return $row->invocation->invoice_number;
                    })
                    ->addColumn('amount', function ($row) {
                        return $row->amount;
                    })
                    ->addColumn('trx_type', function ($row) {
                        return $row->trx_type;
                    })
                    ->addColumn('trx_method', function ($row) {
                        return $row->trx_method;
                    }) ->addColumn('status', function ($row) {
                        return $row->invocation->status;
                    })
                    ->addColumn('created_date', function ($row) {
                        return carbon($row->created_at)->format('d M, Y');
                    })->addColumn('actions', function ($row) {
                        $this->setData('transaction', $row);
                        return $this->view('pages.web.transaction.action.action-datatable');
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            } catch (\Yajra\DataTables\Exceptions\Exception $e) {
                logError($e, title: 'transaction - datatable');
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
     * @throws Exception
     */
    public function index(): View
    {
        $this->setPageTitle('Transaksi');
        $this->setBreadCrumb('Transaksi');

        return $this->view('pages.web.transaction.index');
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
            $lastBCN = Tenant::query()->max('bcn');
            setDefaultRequest('bcn', $lastBCN + 1);
            return \response()->json([
                'view' => $this->view('pages.web.transaction.modals.modal-create-transaction')->render(),
            ]);
        }
        abort(404);
    }

}
