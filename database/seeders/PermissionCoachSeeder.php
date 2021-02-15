<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;

class PermissionCoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $permissions = [
                'coach_dashboard',
            ];

            // $coachRole = Role::where('name', 'coach')->first();

            foreach ($permissions as $key => $permission) {
                if (null != $permission) {
                    $p = Permission::firstOrCreate([
                        'name' => $permission,
                        'guard_name' => 'coach',
                    ],[
                        'id' => Uuid::uuid4()->toString()
                    ]);
                    // $coachRole->givePermissionTo($p);
                }
            }
        });
    }
}
