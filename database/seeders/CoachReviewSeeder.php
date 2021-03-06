<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CoachReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
        	$coachs = \App\Models\Coach::all();

            foreach ($coachs as $key => $coach) {
                if ($coach != null) {
                    \App\Models\CoachReview::firstOrCreate([
                        'coach_id' => $coach['id'],
                    ]);
                }
            }
        });
    }
}
