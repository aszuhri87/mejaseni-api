<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class GalerySeeder extends Seeder
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
                    'title' => 'Galery - Mejaseni Virtual Festival 1',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    
                ],
                [
                    'title' => 'Galery - Mejaseni Virtual Festival 2',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner2.jpg",
                ],
                [
                    'title' => 'Galery - Mejaseni Virtual Festival 3',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner2.jpg",
                ],
                [
                    'title' => 'Galery - Mejaseni Virtual Festival 4',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\Galery::firstOrCreate([
                        'title' => $seeder['title'],
                        'description' => $seeder['description'],
                        'image' => $seeder['image'],
                    ]);
                }
            }
        });
    }
}
