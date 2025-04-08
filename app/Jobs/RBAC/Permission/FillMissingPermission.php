<?php

namespace App\Jobs\RBAC\Permission;

use App\Enums\Permissions\RegisterPermissions;
use App\Models\Spatie\Permission;
use App\Services\PermissionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FillMissingPermission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        foreach (RegisterPermissions::cases() as $registeredPermission) {
            $this->checkEmptyPermission(registeredPermission: $registeredPermission->value);
        }
    }

    /**
     * @param $registeredPermission
     * @return void
     * @throws \Exception
     */
    protected function checkEmptyPermission($registeredPermission): void
    {
        $permissions = $registeredPermission;
        $getPermissions = Permission::query()
            ->select('name')
            ->whereIn('name', $permissions::values())
            ->pluck('name');

        $emptyPermissions = array_diff($permissions::values(), $getPermissions->toArray());

        $this->handleEmptyPermission(permissions: $permissions, emptyPermissions: $emptyPermissions);
    }


    /**
     * @param $permissions
     * @param array $emptyPermissions
     * @return void
     * @throws \Exception
     */
    protected function handleEmptyPermission($permissions, array $emptyPermissions): void
    {
        collect($emptyPermissions)
            ->each(function ($newPermissionName) use ($permissions) {
                (new PermissionService())->createPermission(permission: $permissions::tryFrom($newPermissionName));
            });
    }

}
