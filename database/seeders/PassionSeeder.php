<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PassionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
            $admin = \App\Models\Passion::updateOrCreate([
                'title' => 'Kembangkan Bakatmu',
                'description' => 'Bersama Coach kami yang terdiri dari para Profesional di bidangnya.',
            ]);
        });
    }
}
