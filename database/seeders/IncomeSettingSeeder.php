<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IncomeSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        error_log(date('Y-m-d', strtotime('friday previous week')).' 17:00:00');
        error_log(date('Y-m-d', strtotime('friday this week')).' 17:00:00');
        error_log(date('Y-m-d', strtotime('friday next week')).' 17:00:00');

        $data = \App\Models\IncomeSetting::firstOrCreate([
            'day' => 'friday',
            'last_request' => '17:00:00'
        ]);

        error_log($data->day);
    }
}
