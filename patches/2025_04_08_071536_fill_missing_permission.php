<?php

use App\Jobs\RBAC\Permission\FillMissingPermission;
use Dentro\Patcher\Patch;

return new class extends Patch
{
    public function __construct()
    {
        $this->isPerpetual = isNonProduction();
    }

    public function eligible()
    {
        return true;
    }

    /**
     * Run patch script.
     *
     * @return void
     */
    public function patch(): void
    {
        dispatch_sync(new FillMissingPermission());
    }
};
