<?php

namespace App\Concerns;

use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $tenant_id
 * @property Tenant $tenant
 */
trait HasTenant
{

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
}
