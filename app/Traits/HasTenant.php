<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTenant
{
    /**
     * @param Builder $query
     * @param int $tenantId
     * @return Builder
     */
    public function scopeTenantId(Builder $query, int $tenantId): Builder
    {
        if (!$tenantId) return $query;

        return $query->where("{$this->table}.tenant_id", $tenantId);
    }

    public function getTenantColumnName()
    {
        $tenantColumn = 'tenant_id';
        if (property_exists($this, 'tenantColumn')) {
            $tenantColumn = $this->tenantColumn;
        }
        return $tenantColumn;
    }

    /**
     * @return BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, $this->getTenantColumnName());
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where($this->getTenantColumnName(), $tenantId);
    }

    public function scopeByInTenant($query, $tenantIds)
    {
        return $query->whereIn($this->getTenantColumnName(), $tenantIds);
    }

    public function getTenantColumnName()
    {
        $tenantColumn = 'tenant_id';
        if (property_exists($this, 'tenantColumn')) {
            $tenantColumn = $this->tenantColumn;
        }
        return $tenantColumn;
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where($this->getTenantColumnName(), $tenantId);
    }

    public function scopeByInTenant($query, $tenantIds)
    {
        return $query->whereIn($this->getTenantColumnName(), $tenantIds);
    }
}
