<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasTenant
{

    protected int $tenantId;

    /**
     * @param int $tenantId
     * @return Builder
     */
    public function byTenant(int $tenantId): Builder
    {
        $this->tenantId = $tenantId;
        $this->query->where('tenant_id', $tenantId);

        return $this->query;
    }
}
