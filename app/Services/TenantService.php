<?php

namespace App\Services;

use App\Models\Tenant\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class TenantService
{
    private Builder $query;
    private Tenant $tenant;

    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = Tenant::query();
    }

    public function tenantId(int $tenantId = null)
    {
        $this->query->tenantId($tenantId ?? $tenantId);
    }

    public function setAvatar(Request $request)
    {
        $tenant =
        $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
    }

    public function unsetAvatar()
    {

    }

    public function get()
    {
        return $this->query->first();
    }

    public function tenant(): Tenant
    {
        if ($this->query->count() > 1) {
            if (isset($this->tenant) and $this->tenant instanceof Tenant) {
                $tenant = $this->tenant;
            } else {
                throw new Exception('Tenant belum di konfigurasi');
            }
        } else {
            $tenant = $this->query->first();
        }

        return $tenant;
    }

}
