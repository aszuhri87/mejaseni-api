<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CoachReview extends Seeder
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
                if ($branch != null) {
                    \App\Models\Branch::firstOrCreate([
                        'name' => $branch['name'],
                        'telephone' => $branch['telephone'],
                        'address' => $branch['address'],
                        'company_id' => $branch['company_id']
                    ]);
                }
            }
        });
    }
}
