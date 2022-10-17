<?php

namespace Database\Seeders;

use App\Models\Spatie\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $seedRoles = [
            Role::RoleSA,
            Role::RoleAdmin,
        ];

        foreach ($seedRoles as $key => $role) {
            Role::create([
                'name' => $role,
            ]);
        }
    }
}
