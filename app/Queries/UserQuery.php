<?php

namespace App\Queries;

use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\User;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class UserQuery extends BaseQueryBuilder
{

    public function getBaseQuery(): Builder
    {
        return User::query();
    }

    public function applyFilterParams(): void
    {
        // search
        $this->builder->when(!empty(request()->input('search.value')), function (Builder $builder) {
            $builder->where('name', '=', request()->input('search.value'));
        });
        $this->builder->when(!empty(request()->input('q')), function (Builder $builder) {
            $builder->where('name', 'ilike', '%' .request()->input('q') . '%');
        });

    }
}
