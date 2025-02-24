<?php

namespace App\Queries;

use App\Models\VA\VirtualAccount;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class JamaahBalanceQuery extends BaseQueryBuilder
{

    public function getBaseQuery(): Builder
    {
        return VirtualAccount::query()
            ->has('vaable')
            ->orderBy('created_at', 'desc')
            ->with(['vaable']);
    }

    public function applyFilterParams(): void
    {
        $this->builder->when(!empty(request()->input('date_from')), function (Builder $query) {
            $query->where('created_at', '>=', request()->input('date_from'));
        });

        $this->builder->when(!empty(request()->input('date_to')), function (Builder $query) {
            $query->where('created_at', '<=', request()->input('date_to'));
        });

        $this->builder->when(!empty(request()->input('saving_type')), function (Builder $query) {
            $query->where('va_label', '<=', request()->input('saving_type'));
        });
    }
}
