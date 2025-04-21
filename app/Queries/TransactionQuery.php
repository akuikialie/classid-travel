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
            ->has('user')
            ->with(['invocation', 'user']);
    }

    public function applyFilterParams(): void
    {
        $search = request()->input('search.value');
        $this->builder->when(!empty($search), function (Builder $builder) use ($search) {
            $builder->whereHas('invocation', function (Builder $builder) use ($search) {
                // Pencarian pada kolom 'virtual_account' dan 'invoice_number' dengan 'ilike'
                $builder->where('virtual_account', 'ilike', '%' . $search . '%')
                    ->orWhere('invoice_number', 'ilike', '%' . $search . '%');
            });
        });
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
