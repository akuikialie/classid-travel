<?php

use App\Enums\Permissions\RegisterPermissions;
use App\Models\Spatie\Permission;
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
        $roles = \App\Models\Spatie\Role::query()
            ->whereIn('name', [\App\Enums\RoleEnum::SuperAdministrator->value, \App\Enums\RoleEnum::Admin->value])
            ->get();

        foreach ($roles as $role) {
            $this->bindNewPermissions($role);
        }

    }

    private function bindNewPermissions(\App\Models\Spatie\Role $role)
    {
        $role->permissions()->sync([]);
        foreach (RegisterPermissions::cases() as $registeredPermission) {
            $permissionClass = $registeredPermission->value;
            $newPermissions = $this->getPermissionByGroup(registeredPermission: $permissionClass);

            $role->permissions()->syncWithoutDetaching($newPermissions->pluck('id')->toArray());
        }
    }

    private function getPermissionByGroup($registeredPermission)
    {
        $permissions = $registeredPermission;
        return Permission::query()
            ->whereIn('name', $permissions::values())
            ->get();
    }
};
