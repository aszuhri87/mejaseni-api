<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class CareerSeeder extends Seeder
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
                    'title' => 'Marketing',
                    'placement' => 'Yogyakarta, Indonesia',
                    'type' => 1,
                    'job_description' => [
                    	[
                    		"description" => "Bertanggung Jawab"
                    	],
                    	[
                    		"description" => "Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. veniam consequat sunt nostrud amet."
                    	]
                    ],
                    'job_requirement' => [
                    	[
                    		"description" => "Bertanggung Jawab"
                    	],
                    	[
                    		"description" => "Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. veniam consequat sunt nostrud amet."
                    	]
                    ]
                ],
                [
                    'title' => 'Profesional Coach Piono',
                    'placement' => 'Yogyakarta, Indonesia',
                    'type' => 2,
                    'job_description' => [
                    	[
                    		"description" => "Bertanggung Jawab"
                    	],
                    	[
                    		"description" => "Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. veniam consequat sunt nostrud amet."
                    	]
                    ],
                    'job_requirement' => [
                    	[
                    		"description" => "Bertanggung Jawab"
                    	],
                    	[
                    		"description" => "Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. veniam consequat sunt nostrud amet."
                    	]
                    ]
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    $result = \App\Models\Career::firstOrCreate([
                        'title' => $seeder['title'],
                        'placement' => $seeder['placement'],
                        'type' => $seeder['type']
                    ]);


                    foreach ($seeder['job_description'] as $key => $value) {
                    	\App\Models\JobDescription::firstOrCreate([
                    		'description' => $value['description'],
                    		'career_id' => $result->id
                    	]);
                    }


                    foreach ($seeder['job_requirement'] as $key => $value) {
                    	\App\Models\JobRequirement::firstOrCreate([
                    		'description' => $value['description'],
                    		'career_id' => $result->id
                    	]);
                    }
                }
            }
        });
    }
}
