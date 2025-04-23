<?php

use App\Concerns\InteractsWithMutation;
use App\Enums\MutationInfo;
use Dentro\Patcher\Patch;

return new class extends Patch {
    use InteractsWithMutation;

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
        $mutations = \App\Models\Mutation\Mutation::query()
            ->with(['tenant'])
            ->where('fee_admin', '=', 0)
            ->get();

        // create mutation admin fee
        /** @var \App\Models\Mutation\Mutation $mutation */
        foreach ($mutations as $mutation) {
            $tenant = $mutation->tenant;
            $mutation->fee_admin = $tenant->fee_admin;
            $mutation->save();
        }
    }
};
