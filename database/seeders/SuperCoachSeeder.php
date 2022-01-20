<?php

namespace Database\Seeders;

use App\Models\Coach;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class SuperCoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coach = Coach::where('username', 'coach')->first();

        $role = Role::firstOrCreate([
            'name' => 'Super Coach',
            'guard_name' => 'coach'
        ],[
            'id' => Uuid::uuid4()->toString(),
        ]);

        $coach->assignRole('Super Coach');
    }
}
