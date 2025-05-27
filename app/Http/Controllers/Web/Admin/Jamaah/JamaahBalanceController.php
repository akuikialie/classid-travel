<?php

namespace App\Http\Controllers\Web\Admin\Jamaah;

use App\Enums\VirtualAccount;
use App\Http\Controllers\Web\Admin\Controller;
use App\Models\User;
use App\Queries\JamaahBalanceQuery;
use App\Services\VirtualAccountService;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function response;

class JamaahBalanceController extends Controller
{

    protected string $forPage = 'jamaah-balance';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setBreadCrumb(['title' => 'Saldo Jamaah', 'url' => routed('admin.jamaah-balance.index')]);
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
                $request = request();

                $filter = $request->input('filter');
                if ($filter) {
                    $request->mergeIfMissing(extract_filters($filter));
                }

                $custom_filter = $request->input('custom');
                if ($custom_filter) {
                    $request->mergeIfMissing($custom_filter);
                }

                $transactions = JamaahBalanceQuery::byTenant(activeTenant()->id)
                    ->filterColumn()
                    ->orderColumn()
                    ->build();

                return datatables()->eloquent($transactions)
                    ->filter(function (Builder $query) use ($request) {
                    })
                    ->addIndexColumn()

                    ->addColumn('owner', function ($row) {
                        if ($row->va_label == VirtualAccount::Tabungan->value) {
                            return $row->vaable?->name;
                        }
                        return $row->vaable?->user?->name;
                    })

                    ->addColumn('saving_name', function ($row) {
                        return $row->va_label == VirtualAccount::Tabungan->value
                            ? 'Tabungan Pribadi'
                            : $row->name;
                    })

                    ->addColumn('virtual_number', fn($row) => $row->va_number)

                    ->orderColumn('virtual_number', function ($query, $order) {
                        $query->orderBy('va_number', $order);
                    })

                    ->addColumn('saving_type', fn($row) => $row->va_label)

                    ->addColumn('balance', fn($row) => 'Rp. ' . moneyFormat($row->balance))
                    ->orderColumn('balance', fn($query, $order) => $query->orderBy('balance', $order))

                    ->addColumn('usd_balance', fn($row) => '$' . moneyFormat($row->usd_balance ?? 0))
                    ->orderColumn('usd_balance', fn($query, $order) => $query->orderBy('usd_balance', $order))

                    ->addColumn('created_at', fn($row) => carbon($row->created_at)->format('d M Y'))
                    ->orderColumn('created_at', fn($query, $order) => $query->orderBy('created_at', $order))

                    ->addColumn('actions', function ($row) {
                        $this->setData('virtual_account', $row);
                        return $this->view('pages.web.jamaah-balance.action.action-datatable');
                    })

                    ->rawColumns(['actions'])

                    ->make();

            } catch (\Yajra\DataTables\Exceptions\Exception $e) {
                logError($e, title: 'transaction - datatable');
                if (isDevelopmentMode()) {
                    throw $e;
                }
                throw new Exception('Terjadi kesalahan!');
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

    /**
     * @param \App\Models\VA\VirtualAccount $virtualAccount
     * @return JsonResponse
     * @throws Exception
     */
    public function convertBalanceView(\App\Models\VA\VirtualAccount $virtualAccount): JsonResponse
    {
        if (\request()->ajax()) {
            $this->setData('virtualAccount', $virtualAccount);
            return response()->json([
                'view' => $this->view('pages.web.jamaah-balance.modal.balance-exchange')->render(),
            ]);
        }
        abort(404);
    }

    /**
     * @throws Exception
     */
    public function convertBalance(Request $request, \App\Models\VA\VirtualAccount $virtualAccount): RedirectResponse
    {
        $request->validate([
            'amount_to_convert' => ['required', 'numeric', 'gt:0'],
            'currency_exchange_rate' => ['required', 'numeric', 'gt:0'],
        ]);

        /** @var User $userActor */
        $userActor = auth()->user();

        $service = new VirtualAccountService($virtualAccount->tenant_id);

        $service->convertCurrency($userActor, $virtualAccount, $request->input());

        notify('Berhasil', 'Berhasil mengubah saldo ke USD', 'success');
        return redirect(routed('admin.jamaah-balance.index'));
    }

}
