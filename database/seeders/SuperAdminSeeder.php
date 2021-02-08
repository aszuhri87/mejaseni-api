<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;
use App\Models\Admin;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('username', 'admin')->first();

        $role = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ],[
            'id' => Uuid::uuid4()->toString(),
        ]);

        $admin->assignRole('Super Admin');
    }
}
