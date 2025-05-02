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
        if (!empty(request()->input('search.value'))){
            $this->search(request()->input('search.value'));
        }

        if (!empty(request()->input('q'))){
            $this->search(request()->input('q'));
        }
    }

    private function search(string $search)
    {
        $this->builder->when(!empty($search), function (Builder $builder) use ($search) {
            $builder
                ->where('name', 'ilike', '%' . $search . '%')
                ->orWhere('phone', 'ilike', '%' . $search . '%')
                ->orWhere('username', 'ilike', '%' . $search . '%');
        });
    }
}
