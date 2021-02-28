<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class SosmedSeeder extends Seeder
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
                    'name' => 'instagram',
                    'url' => '#',
                    'image' => "media/img/instagram-logo.png",
                    
                ],
                [
                    'name' => 'linkedin',
                    'url' => '#',
                    'image' => "media/img/logo-linkedin.png",
                ],
                [
                    'name' => 'facebook',
                    'url' => '#',
                    'image' => "media/svg/logo-facebook.svg",
                ],
                [
                    'name' => 'twitter',
                    'url' => '#',
                    'image' => "media/svg/logo-twitter.svg",
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\SocialMedia::firstOrCreate([
                        'name' => $seeder['name'],
                        'url' => $seeder['url'],
                        'image' => $seeder['image'],
                    ]);
                }
            }
        });
    }
}
