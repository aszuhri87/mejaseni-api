<?php

namespace Database\Seeders;

use App\Models\Expertise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpertiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
            $Expertise = Expertise::updateOrCreate([
                'name' => 'Musik',
            ],
            [                           
                'name' => 'Musik',
            ]);
        });
    }
}
