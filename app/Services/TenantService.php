<?php

namespace App\Services;

use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Request;

class TenantService
{

    protected $query;
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
        $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
    }

    public function unsetAvatar()
    {

    }

}
