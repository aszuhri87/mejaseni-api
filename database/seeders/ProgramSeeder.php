<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProgramSeeder extends Seeder
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
                    'name' => 'Regular Class',
                    'description' => 'Kelas dengan sistem one on one secara daring diadakan dengan paket kurikulum yang menyeluruh serta frekuensi pertemuan yang disesuaikan dengan silabus.',
                    'image' => "media/svg/regular-class__icon.svg",
                    
                ],
                [
                    'name' => 'Spesial Class',
                    'description' => 'Kelas ini menawarkan subyek pelajaran yang spesifik dengan satu kali layanan dan pertemuan serta digelar one on one secara daring.',
                    'image' => "media/svg/special-class__icon.svg",
                ],
                [
                    'name' => 'Master Lesson',
                    'description' => 'Menggunakan sistem one to many dan digelar secara berkala, coaching clinic hadir untuk menuntaskan kendalamu dalam belajar di Mejaseni!',
                    'image' => "media/svg/master-lesson__icon.svg",
                ],
                [
                    'name' => 'Video Course',
                    'description' => 'Video-video tutorial yang memuat satu dan beragam subyek, dengan sistem paket atau satuan, serta dapat diunduh di gawaimu!',
                    'image' => "media/svg/video-course.svg",
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\Program::firstOrCreate([
                        'name' => $seeder['name'],
                        'description' => $seeder['description'],
                        'image' => $seeder['image'],
                    ]);
                }
            }
        });
    }
}
