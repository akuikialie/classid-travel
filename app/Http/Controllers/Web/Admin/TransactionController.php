<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Exports\TransactionExport;
use App\Models\Tenant\Tenant;
use App\Queries\TransactionQuery;
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
            try {

                $filter = request()->input('filter');

                if (isset($filter)) {
                    request()->mergeIfMissing(extract_filters($filter));
                }

                $custom_filter = request()->input('custom');
                if (isset($custom_filter)) {
                    request()->mergeIfMissing($custom_filter);
                }

                $transactions = TransactionQuery::withSum('mutations', 'fee_admin')
                    ->byTenant(activeTenant()->id)
                    ->filterColumn()
                    ->orderColumn()
                    ->build();
                return datatables()->eloquent($transactions)
                    ->filter(function (Builder $query) use ($request) {

                    })
                    ->addIndexColumn()
                    ->addColumn('owner', function ($row) {
                        $ownerName = $row->user?->name ?? '-';
                        $ownerPhone = $row->user?->phone ?? '-';
                        return "{$ownerName} | {$ownerPhone}";
                    })->addColumn('virtual_account', function ($row) {
                        return $row->invocation->virtual_account;
                    })
                    ->addColumn('invoice_number', function ($row) {
                        return $row->invocation->invoice_number;
                    })
                    ->addColumn('amount', function ($row) {
                        return 'Rp. ' . moneyFormat($row->amount);
                    })
                    ->orderColumn('amount', fn($query, $order) => $query->orderBy('amount', $order))
                    ->addColumn('trx_type', function ($row) {
                        return $row->trx_type;
                    })
                    ->addColumn('trx_method', function ($row) {
                        return $row->trx_method;
                    })->addColumn('status', function ($row) {
                        return $row->invocation->status;
                    })
                    ->addColumn('fee_admin', function ($row) {
                        return $row->mutations_sum_fee_admin;
                    })
                    ->addColumn('created_date', function ($row) {
                        return carbon($row->trx_date)->format('d M, Y');
                    })
                    ->orderColumn('created_date', fn($query, $order) => $query->orderBy('trx_date', $order))
                    ->addColumn('actions', function ($row) {
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

        $transactionMethods = TransactionMethod::cases();
        $transactionTypes = TransactionType::cases();

        $this->setData('transactionMethods', $transactionMethods);
        $this->setData('transactionTypes', $transactionTypes);
        return $this->view('pages.web.transaction.index');
    }

    /**
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function download()
    {
        $transactions = TransactionQuery::filterColumn()
            ->orderColumn()
            ->build()
            ->latest('id');
        return (new TransactionExport($transactions))->download();
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
