<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Exports\TransactionExport;
use App\Models\Tenant\Tenant;
use App\Queries\MutationQuery;
use App\Queries\TransactionQuery;
use App\Traits\FragmentRenderer;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

#[Prefix('mutation')]
#[Name('mutation', false, true)]
#[Middleware(['auth:sanctum'])]
class MutationController extends Controller
{
    use FragmentRenderer;

    protected string $forPage = 'mutation';

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
    #[Post('datatable', name: 'datatable')]
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

                $mutations = MutationQuery::/*byTenant(activeTenant()->id)
                    ->*/filterColumn()
                    ->orderColumn()
                    ->build();
                return datatables()->eloquent($mutations)
                    ->filter(function (Builder $query) use ($request) {

                    })
                    ->addIndexColumn()
                    ->addColumn('invoice_number', function ($row) {
                        return $row->transaction->invocation->invoice_number;
                    })
                    ->addColumn('mutable', function ($row) {
                        return $row->mutable->getMutableName();
                    })->addColumn('type', function ($row) {
                        return $row->type;
                    })
                    ->addColumn('info', function ($row) {
                        return $row->info;
                    })
                    ->addColumn('amount_before', function ($row) {
                        return 'Rp. ' . moneyFormat($row->amount_before);
                    })
                    ->addColumn('amount', function ($row) {
                        return 'Rp. ' . moneyFormat($row->amount);
                    })
                    ->addColumn('amount_after', function ($row) {
                        return 'Rp. ' . moneyFormat($row->amount_after);
                    })
                    ->addColumn('created_date', function ($row) {
                        return carbon($row->created_at)->toDateTimeString();
                    })
                    ->orderColumn('created_date', fn($query, $order) => $query->orderBy('created_at', $order))
                    ->make(true);
            } catch (\Yajra\DataTables\Exceptions\Exception $e) {
                logError($e, title: 'mutation - datatable');
                if (isDevelopmentMode()) {
                    throw $e;
                }
                throw new Exception('Terjadi kesalahan!.');
            }
        }
    }
}
