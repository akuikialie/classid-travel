<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SeedPermissions::class);
        $this->call(SeedRoles::class);
        $this->call(SeedTenant::class);
        $this->call(SeedInsertCredentials::class);
        $this->call(SeedUsers::class);
        $this->call(SeedPlans::class);
        $this->call(SeedFacilities::class);
        $this->call(SeedDestinations::class);
        $this->call(SeedPackages::class);
        $this->call(SeedCity::class);


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
