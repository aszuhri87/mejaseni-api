<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
            $seeders = [
                [
                    'name' => 'Mejaseni Virtual Festival 1',
                    'position' => 'CEO',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/coach.png",
                    
                ],
                [
                    'name' => 'Mejaseni Virtual Festival 2',
                    'position' => 'CTO',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/coach.png",
                ],
                [
                    'name' => 'Mejaseni Virtual Festival 3',
                    'position' => 'VP',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/coach2.png",
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\Team::firstOrCreate([
                        'name' => $seeder['name'],
                        'position' => $seeder['position'],
                        'description' => $seeder['description'],
                        'image' => $seeder['image'],
                    ]);
                }
            }
        });
    }
}
