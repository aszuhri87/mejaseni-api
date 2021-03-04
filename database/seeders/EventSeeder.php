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
                    'start_at' => '2021-02-12 10:00:00',
                    'end_at' => '2021-02-12 11:00:00',
                    'is_free' => true,
                    'total' => 0,
                    'quota' => 10,
                    'location' => 'PT SEMUA APLIKASI INDONESIA'
                ],
                [
                    'title' => 'Mejaseni Virtual Festival 2',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    'start_at' => '2021-02-12 10:00:00',
                    'end_at' => '2021-02-12 11:00:00',
                    'is_free' => false,
                    'total' => 100000,
                    'quota' => 10,
                    'location' => 'PT SEMUA APLIKASI INDONESIA'
                ],
                [
                    'title' => 'Mejaseni Virtual Festival 3',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    'start_at' => '2021-02-12 10:00:00',
                    'end_at' => '2021-02-12 11:00:00',
                    'is_free' => false,
                    'total' => 10000,
                    'quota' => 10,
                    'location' => 'PT SEMUA APLIKASI INDONESIA'
                ],
                [
                    'title' => 'Mejaseni Virtual Festival 4',
                    'description' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit facere facilis consequuntur inventore velit suscipit distinctio quod iure, repellendus ad. Alias, rerum!',
                    'image' => "media/master-lesson__banner.jpg",
                    'start_at' => '2021-02-12 10:00:00',
                    'end_at' => '2021-02-12 11:00:00',
                    'is_free' => true,
                    'total' => 0,
                    'quota' => 10,
                    'location' => 'PT SEMUA APLIKASI INDONESIA'
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\Event::firstOrCreate([
                        'title' => $seeder['title'],
                        'description' => $seeder['description'],
                        'image' => $seeder['image'],
                        'start_at' => $seeder['start_at'],
                        'end_at' => $seeder['end_at'],
                        'is_free' => $seeder['is_free'],
                        'total' => $seeder['total'],
                        'location' => $seeder['location'],
                        'quota' => $seeder['quota']
                    ]);
                }
            }
        });
    }
}
