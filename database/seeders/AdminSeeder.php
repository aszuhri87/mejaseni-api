<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
            $admin = \App\Models\Admin::updateOrCreate([
                'email' => 'admin@example.com',
            ],
            [
                'username' => 'admin',
                'name' => 'Admin Example',
                'password' => \Hash::make(123456),
            ]);
        });
    }
}
