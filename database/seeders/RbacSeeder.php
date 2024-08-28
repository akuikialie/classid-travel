<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use App\Models\Spatie\Permission;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedPermission();
        $this->seedRole();
    }

    private function seedPermission(): void
    {
        $pages = [
            'role',
            'travel',
            'user',
            'destination',
            'package',
            'facility',
            'schedule',
            'itinerary',
        ];

        $default = [
            'view',
            'create',
            'update',
            'delete',
        ];

        foreach ($pages as $page){
            foreach ($default as $permission){
                $label = "{$permission} {$page}";
                Permission::query()
                    ->create([
                        'label' => $label,
                        'name' => $label,
                        'guard_name' => 'web',
                        'group' => $page,
                        'type' => PermissionType::tenant->value,
                    ]);
            }
        }
    }

    private function seedRole(): void
    {
        $seedRoles = [
            [
                'label' => 'Super Administrator',
                'name' => 'super-administrator',
                'type' => PermissionType::tenant->keyValue(),
            ],
            [
                'tenant_id' => 1,
                'label' => 'Administrator',
                'name' => 'administrator',
                'type' => PermissionType::tenant->keyValue(),
            ],
            [
                'label' => 'Jamaah',
                'name' => 'jamaah',
                'type' => PermissionType::app->keyValue(),
            ],
        ];

        $permissions = Permission::query()->get()->pluck('id')->toArray();

        foreach ($seedRoles as $role){
            DB::transaction(function () use ($role, $permissions) {
                $permissionService = new PermissionService($role['tenant_id'] ?? null);
                $permissionService
                    ->createNewRole($role)
                    ->syncPermissions(permissions: $permissions);
            });
        }
    }
}
