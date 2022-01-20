<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class WorkHourSeeder extends Seeder
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
                    'number' => 7,
                    'day' => 'Sunday',
                    'open' => "8:00",
                    'close' => "17:00",
                    'is_closed' => true
                ],
                [
                    'number' => 1,
                    'day' => 'Monday',
                    'open' => "8:00",
                    'close' => "17:00",
                    'is_closed' => false
                ],
                [
                    'number' => 2,
                    'day' => 'Tuesday',
                    'open' => "8:00",
                    'close' => "17:00",
                    'is_closed' => false
                ],
                [
                    'number' => 3,
                    'day' => 'Wednesday',
                    'open' => "8:00",
                    'close' => "17:00",
                    'is_closed' => false
                ],
                [
                    'number' => 4,
                    'day' => 'Thursday',
                    'open' => "8:00",
                    'close' => "17:00",
                    'is_closed' => false
                ],
                [
                    'number' => 5,
                    'day' => 'Friday',
                    'open' => "8:00",
                    'close' => "17:00",
                    'is_closed' => false
                ],
                [
                    'number' => 6,
                    'day' => 'Saturday',
                    'open' => "8:00",
                    'close' => "17:00",
                    'is_closed' => true
                ],
            ];

            foreach ($seeders as $key => $seeder) {
                if ($seeder != null) {
                    \App\Models\WorkingHour::firstOrCreate([
                        'number' => $seeder['number'],
                        'day' => $seeder['day'],
                        'open' => $seeder['open'],
                        'close' => $seeder['close'],
                        'is_closed' => $seeder['is_closed'],
                    ]);
                }
            }
        });
    }
}
