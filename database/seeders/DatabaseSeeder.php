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
        $this->call([
            ExpertiseSeeder::class,
            AdminSeeder::class,
            SuperAdminSeeder::class,
            PermissionAdminSeeder::class,
            CoachSeeder::class,
            SuperCoachSeeder::class,
            PermissionCoachSeeder::class,
            SosmedSeeder::class
        ]);
    }
}
