<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class TutorialVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
            $classroom_categories = \App\Models\ClassroomCategory::all();

            foreach ($classroom_categories as $key => $item) {
                $item->update([
                	'empty_image' => 'media/svg/empty-store.svg',
                	'empty_message' => 'Wah, video course yang kamu cari belum dibuat nih'
                ]);
            }
        });
    }
}
