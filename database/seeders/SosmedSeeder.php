<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

use App\Models\Sosmed;
use DB;

class SosmedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sosmeds = [
            [
                'name' => 'Instagram',
                'slug' => 'instagram'
            ],
            [
                'name' => 'Facebook',
                'slug' => 'facebook'
            ],
            [
                'name' => 'Twitter',
                'slug' => 'twitter'
            ],
        ];

        foreach ($sosmeds as $key => $sosmed) {
            if ($sosmed != null) {
                Sosmed::firstOrCreate([
                    'name' => $sosmed['name'],
                    'slug' => $sosmed['slug']
                ],[
                    'id' => Uuid::uuid4()->toString()
                ]);
            }
        }

    }
}
