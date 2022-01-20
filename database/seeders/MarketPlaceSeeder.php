<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MarketPlaceSeeder extends Seeder
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
                    'name' => 'tokopedia',
                    'url' => '#',
                    'image' => "media/svg/tokopedia-logo.svg",
                    
                ],
                [
                    'name' => 'shopee',
                    'url' => '#',
                    'image' => "media/svg/shopee-logo.svg",
                ],
                [
                    'name' => 'blibli',
                    'url' => '#',
                    'image' => "media/svg/blibli-logo.svg",
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\MarketPlace::firstOrCreate([
                        'name' => $seeder['name'],
                        'url' => $seeder['url'],
                        'image' => $seeder['image'],
                    ]);
                }
            }
        });
    }
}
