<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\VirtualAccount;
use App\Queries\JamaahBalanceQuery;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JamaahBalanceController extends Controller
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setBreadCrumb(['title' => 'Saldo Jamaah', 'url' => routed('admin.jamaah-balance.index')]);
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

                $transactions = JamaahBalanceQuery::byTenant(activeTenant()->id)
                    ->filterColumn()
                    ->orderColumn()
                    ->build()
                    ->latest('id');

                return datatables()->eloquent($transactions)
                    ->filter(function (Builder $query) use ($request) {

                    })
                    ->addIndexColumn()
                    ->addColumn('owner', function ($row) {
                        if ($row->va_label == VirtualAccount::Tabungan->value) {
                            $name = $row->vaable?->name;
                        } else {
                            $name = $row->vaable?->user?->name;
                        }
                        return $name;
                    })->addColumn('saving_name', function ($row) {
                        $name = $row->name;
                        if ($row->va_label == VirtualAccount::Tabungan->value) {
                            $name = 'Tabungan Pribadi';
                        }
                        return $name;
                    })
                    ->addColumn('virtual_number', function ($row) {
                        return $row->va_number;
                    })
                    ->addColumn('saving_type', function ($row) {
                        return $row->va_label;
                    })
                    ->addColumn('balance', function ($row) {
                        return 'Rp. ' . moneyFormat($row->balance);
                    })
                    ->addColumn('usd_balance', function ($row) {
                        return '$' . moneyFormat(0);
                    })
                    ->addColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->format('d M Y');
                    })
                    ->addColumn('actions', function ($row) {
                        $this->setData('virtual_account', $row);
                        return $this->view('pages.web.jamaah-balance.action.action-datatable');
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
     * @return View
     * @throws Exception
     */
    public function index(): View
    {
        $this->setPageTitle('Saldo Jamaah');
        $this->setBreadCrumb('Saldo Jamaah');

        $this->setData('savingTypes', VirtualAccount::cases());

        return $this->view('pages.web.jamaah-balance.index');
    }

}
