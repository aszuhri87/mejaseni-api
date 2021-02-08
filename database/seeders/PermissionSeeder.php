<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Permission;
use Ramsey\Uuid\Uuid;

class PermissionSeeder extends Seeder
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

            ];

            foreach ($permissions as $key => $permission) {
                if ($permission != null) {
                    Permission::firstOrCreate([
                        'name' => $permission,
                        'guard_name' => 'admin'
                    ],[
                        'id' => Uuid::uuid4()->toString()
                    ]);
                }
            }
        });
    }
}
