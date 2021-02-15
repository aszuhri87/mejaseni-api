<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'admin',
                'guard_name' => 'admin',
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'student',
                'guard_name' => 'student',
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'coach',
                'guard_name' => 'coach',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
