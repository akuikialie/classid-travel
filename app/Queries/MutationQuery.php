<?php

namespace App\Queries;

use App\Models\Mutation\Mutation;
use App\Models\VA\VirtualAccount;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class MutationQuery extends BaseQueryBuilder
{

    public function getBaseQuery(): Builder
    {
        return Mutation::query()
            ->with(['transaction']);
    }

    public function applyFilterParams(): void
    {
        $search = request()->input('search.value');
        $this->builder->when(!empty($search), function (Builder $builder) use ($search) {
            $builder->whereHas('invocation', function (Builder $builder) use ($search) {
                // Pencarian pada kolom 'virtual_account' dan 'invoice_number' dengan 'ilike'
                $builder->where(function($qry) use ($search) {
                    $qry->where('virtual_account', 'ilike', '%' . $search . '%')
                        ->orWhere('invoice_number', 'ilike', '%' . $search . '%');
                });
            });
        });

        $this->builder->when(!empty(request()->input('type')), function (Builder $query) {
            $query->where('type', '=', request()->input('type'));
        });

        $this->builder->when(!empty(request()->input('info')), function (Builder $query) {
            $query->where('info', '=', request()->input('info'));
        });

        $this->builder->when(!empty(request()->input('transaction_id')), function (Builder $query) {
            $query->where('transaction_id', '=', request()->input('transaction_id'));
        });

        $this->builder->when(!empty(request()->input('user_id')), function (Builder $query) {
            $query->where('user_id', '=', request()->input('user_id'));
        });

        $this->builder->when(!empty(request()->input('tenant_id')), function (Builder $query) {
            $query->where('tenant_id', '=', request()->input('tenant_id'));
        });

        $this->builder->when(!empty(request()->input('mutable_type')), function (Builder $query) {
            $query->where('mutable_type', '=', request()->input('mutable_type'));
        });

        $this->builder->when(!empty(request()->input('mutable_id')), function (Builder $query) {
            $query->where('mutable_id', '=', VirtualAccount::hashToId(request()->input('mutable_id')));
        });

        $this->builder->when(!empty(request()->input('date_from')), function (Builder $query) {
            $query->where('created_at', '>=', request()->input('date_from'));
        });

        $this->builder->when(!empty(request()->input('date_to')), function (Builder $query) {
            $query->where('created_at', '<=', request()->input('date_to'));
        });

        $this->builder->when(!empty(request()->input('latest')), function (Builder $query) {
            $query->orderBy('created_at', 'desc');
        });
    }
}
