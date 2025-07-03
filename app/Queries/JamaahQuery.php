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
        if (!empty(request()->input('search.value'))) {
            $this->search(request()->input('search.value'));
        }

        if (!empty(request()->input('q'))) {
            $this->search(request()->input('q'));
        }

        $this->builder->when(!empty(request()->input('package_id')), function (Builder $builder) {
            $builder->whereHas('planPackages', function (Builder $builder) {
                $builder->where('plan_packages.id', '=', PlanPackage::hashToId(request()->input('package_id')));
            });
        });

    }

    /**
     * @param string $search
     * @return void
     */
    private function search(string $search): void
    {
        $this->builder->when(!empty($search), function (Builder $builder) use ($search) {
            $builder
                ->whereHas('user', function (Builder $builder) use ($search) {
                    $builder->where(function($qry) use ($search) {
                        $qry->where('name', 'ilike', '%' . $search . '%')
                            ->orWhere('phone', 'ilike', '%' . $search . '%')
                            ->orWhere('username', 'ilike', '%' . $search . '%');
                    });
                });
        });
    }
}
