<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class EventSeeder extends Seeder
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
                    'title' => 'Mejaseni Virtual Festival 1',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    'date' => '12-02-2021'
                ],
                [
                    'title' => 'Mejaseni Virtual Festival 2',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    'date' => '11-02-2021'
                ],
                [
                    'title' => 'Mejaseni Virtual Festival 3',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    'date' => '10-02-2021'
                ],
                [
                    'title' => 'Mejaseni Virtual Festival 4',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    'date' => '9-02-2021'
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\Event::firstOrCreate([
                        'title' => $seeder['title'],
                        'description' => $seeder['description'],
                        'image' => $seeder['image'],
                        'date' => $seeder['date']
                    ]);
                }
            }
        });
    }
}
