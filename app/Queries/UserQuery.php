<?php

namespace App\Queries;

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
        if (!empty(request()->input('search.value'))) {
            $this->search(request()->input('search.value'));
        }

        if (!empty(request()->input('q'))) {
            $this->search(request()->input('q'));
        }

        $this->builder
            ->when(!empty(request()->input('status')), function (Builder $builder) {
                $builder->where('status', '=', request()->input('status'));
            })
            ->when(!empty(request()->input('tenant_id')), function (Builder $builder) {
                $builder->where('tenant_id', '=', request()->input('tenant_id'));
            })->when(!empty(request()->input('is_super')), function (Builder $builder) {
                $builder->where('is_super', '=', request()->input('is_super'));
            });

        $this->builder
            ->when(!empty(request()->input('role')), function (Builder $builder) {
                $builder->whereHas('roles', function (Builder $subQuery) {
                    $subQuery->where('name', request()->input('role'));
                });
            });

        $this->dateFilter('created_at', request()->input('date_from'), request()->input('date_to'));
    }

    /**
     * @param string $columnName
     * @param string|null $columnValue
     * @return void
     */
    private function commonFilter(string $columnName, ?string $columnValue): void
    {
        $this->builder
            ->when(!empty($columnValue), function (Builder $builder) use ($columnName) {
                $builder->where($columnName, '=', request()->input('status'));
            });
    }

    /**
     * @param string $columnName
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @return void
     */
    private function dateFilter(string $columnName = 'created_at', ?string $dateFrom = null, ?string $dateTo = null): void
    {

        $this->builder->when(!empty($dateFrom), function (Builder $query) use ($dateFrom, $columnName) {
            $query->where($columnName, '>=', $dateFrom);
        });

        $this->builder->when(!empty($dateTo), function (Builder $query) use ($columnName, $dateTo) {
            $query->where($columnName, '<=', $dateTo);
        });
    }

    /**
     * @param string|null $search
     * @return void
     */
    private function search(?string $search): void
    {
        $this->builder->when(!empty($search), function (Builder $builder) use ($search) {
            $builder->where(function($qry) {
                $qry->where('name', 'ilike', '%' . $search . '%')
                    ->orWhere('phone', 'ilike', '%' . $search . '%')
                    ->orWhere('username', 'ilike', '%' . $search . '%');
            });
        });
    }
}
