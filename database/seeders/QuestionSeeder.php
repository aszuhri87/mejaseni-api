<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;
class QuestionSeeder extends Seeder
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
                    'name' => 'restureese',
                    'email' => 'restu@varx.id',
                    'phone' => '09889898989',
                    'message' => 'asdsadasd asdasdasd asdasdasdsadasd',                    
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\Question::firstOrCreate([
                        'name' => $seeder['name'],
                        'email' => $seeder['email'],
                        'phone' => $seeder['phone'],
                        'message' => $seeder['message'],
                    ]);
                }
            }
        });
    }
}
