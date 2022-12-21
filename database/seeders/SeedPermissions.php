<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use App\Models\Spatie\Permission;
use Illuminate\Database\Seeder;

class SeedPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
                Permission::query()
                    ->create([
                        'name' => "{$permission} {$page}",
                        'guard_name' => 'web',
                        'group' => $page,
                        'type' => PermissionType::tenant->value,
                    ]);
            }
        }
    }
}
