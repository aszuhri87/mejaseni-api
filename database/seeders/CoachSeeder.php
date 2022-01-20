<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Expertise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function () {
            $expertise = Expertise::where('name','Musik')->first();
            $coach = Coach::updateOrCreate(
                [
                    'email' => 'coach@example.com',
                ],
                [
                    'expertise_id' => $expertise->id,
                    'phone' => '1234567890',
                    'username' => 'coach',
                    'name' => 'Coach Example',
                    'password' => \Hash::make(123456),
                ]
            );
        });
    }
}
