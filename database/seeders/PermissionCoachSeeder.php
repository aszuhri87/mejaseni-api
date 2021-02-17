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
                'coach_schedule',
                'coach_schedule_list',
                'coach_schedule_insert',
                'coach_schedule_update',
                'coach_schedule_delete',
                'coach_schedule_print',
                'coach_materi',
                'coach_materi_list',
                'coach_materi_insert',
                'coach_materi_update',
                'coach_materi_delete',
                'coach_materi_print',
                'coach_exercise',
                'coach_assignment',
                'coach_assignment_list',
                'coach_assignment_insert',
                'coach_assignment_update',
                'coach_assignment_delete',
                'coach_assignment_print',
                'coach_review',
                'coach_review_list',
                'coach_review_insert',
                'coach_review_update',
                'coach_review_delete',
                'coach_review_print',
                'coach_profile',
                'coach_profile_list',
                'coach_profile_insert',
                'coach_profile_update',
                'coach_profile_delete',
                'coach_profile_print',
                'coach_rekening',
                'coach_rekening_list',
                'coach_rekening_insert',
                'coach_rekening_update',
                'coach_rekening_delete',
                'coach_rekening_print',
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
