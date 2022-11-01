<?php

namespace App\Services;

use App\Models\Tenant\Tenant;

class TenantService
{

    public $query;
    public function __construct()
    {
        $this->query = Tenant::query();
    }


}
