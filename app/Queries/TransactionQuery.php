<?php

namespace App\Queries;

use App\Models\Transaction\Transaction;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class TransactionQuery extends BaseQueryBuilder
{

    public function getBaseQuery(): Builder
    {
        return Transaction::query()
            ->with(['invocation', 'user']);
    }

    public function applyFilterParams(): void
    {
        $this->builder->when(!empty(request()->input('date_from')), function (Builder $query) {
            $query->where('trx_date', '>=', request()->input('date_from'));
        });

        $this->builder->when(!empty(request()->input('date_to')), function (Builder $query) {
            $query->where('trx_date', '<=', request()->input('date_to'));
        });

        $this->builder->when(!empty(request()->input('trx_method')), function (Builder $query) {
            $query->where('trx_method', '=', request()->input('trx_method'));
        });

        $this->builder->when(!empty(request()->input('trx_type')), function (Builder $query) {
            $query->where('trx_type', '=', request()->input('trx_type'));
        });
    }
}
