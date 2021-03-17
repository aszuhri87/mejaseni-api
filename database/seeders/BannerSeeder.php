<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class BannerSeeder extends Seeder
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
                    'type' => 'registered',
                    'title' => 'Ayo ikuti kelas favoritmu!',
                    'description' => 'Buktikan sendiri bahwa mejaseni bisa membantumu mengembangkan keahlianmu, seperti yang telah dilakukan oleh ratusan ribu member lainnya.',
                    'image' => "media/img/banner.jpg",

                ],
                [
                    'type' => 'unregistered',
                    'title' => 'Ayo bergabung dan mulai asah skillmu!',
                    'description' => 'Buktikan sendiri bahwa mejaseni bisa membantumu mengembangkan keahlianmu, seperti yang telah dilakukan oleh ratusan ribu member lainnya.',
                    'image' => "media/img/cta-banner.jpg",

                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\Banner::firstOrCreate([
                        'type' => $seeder['type'],
                        'title' => $seeder['title'],
                        'description' => $seeder['description'],
                        'image' => $seeder['image']
                    ]);
                }
            }
        });
    }
}
