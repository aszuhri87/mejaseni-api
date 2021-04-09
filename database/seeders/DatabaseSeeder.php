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
            SosmedSeeder::class,
            CompanySeeder::class,
            BranchSeeder::class,
            EventSeeder::class,
            FaqSeeder::class,
            GalerySeeder::class,
            MarketPlaceSeeder::class,
            NewsSeeder::class,
            PrivacyPolicySeeder::class,
            ProgramSeeder::class,
            // Question::class,
            SocialMediaSeeder::class,
            TeamSeeder::class,
            WorkHourSeeder::class,
            BannerSeeder::class,
            PassionSeeder::class

            // CollectionSeed::class
        ]);
    }
}
