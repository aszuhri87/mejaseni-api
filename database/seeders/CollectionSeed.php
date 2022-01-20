<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Collection;
use App\Models\Student;
use Carbon\Carbon;
use Faker\Provider\Lorem;
use Illuminate\Database\Seeder;

class CollectionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $student = Student::inRandomOrder()->first();
        $assignment = Assignment::inRandomOrder()->first();

        $collecting = Collection::create([
            'student_id' => $student->id,
            'assignment_id' => $assignment->id,
            'name' =>'Tugas_'.Carbon::now(),
            'description' => Lorem::text(200),
            'upload_date' => Carbon::now(),
        ]);
    }
}
