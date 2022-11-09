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

        $table = $this->table.'tenant_id';

        return $query->where($table, $tenantId);
    }


    /**
     * @return BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
