<?php

use Dentro\Patcher\Patch;

return new class extends Patch
{
    public function __construct()
    {
        $this->isPerpetual = isNonProduction();
    }

    public function eligible()
    {
        return false;
    }
    /**
     * Run patch script.
     *
     * @return void
     */
    public function patch(): void
    {
        $permissionGroups = [];
        foreach (\App\Enums\Permissions\RegisterPermissions::cases() as $key => $registerPermissions){
            $permissionGroups[] = [
                'name' => $registerPermissions->value::getGroupName()
            ];
        }

        \App\Models\Spatie\Permission::query()
            ->whereNotIn('group', collect($permissionGroups)->pluck('name')->toArray())
            ->forceDelete();
    }
};
