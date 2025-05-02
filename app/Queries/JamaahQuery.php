<?php

namespace App\Queries;

use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class JamaahQuery extends BaseQueryBuilder
{

    public function getBaseQuery(): Builder
    {
        return Jamaah::query()
            ->with(['user']);
    }

    public function applyFilterParams(): void
    {
        // search
        $this->builder->when(!empty(request()->input('search.value')), function (Builder $builder) {
            $builder->where('va_number', '=', request()->input('search.value'));
        });

        $this->builder->when(!empty(request()->input('package_id')), function (Builder $builder) {
            $builder->whereHas('planPackages', function (Builder $builder) {
                $builder->where('plan_packages.id', '=', PlanPackage::hashToId(request()->input('package_id')));
            });
        });

    }
}
