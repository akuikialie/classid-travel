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
            ->with(['vaable']);
    }

    public function applyFilterParams(): void
    {

    }
}
