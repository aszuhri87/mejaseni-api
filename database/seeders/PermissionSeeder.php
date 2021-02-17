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
                'master',
                'courses',
                'category_class',
                'category_class_list',
                'category_class_insert',
                'category_class_update',
                'category_class_delete',
                'category_class_print',
                'sub_category_class',
                'sub_category_class_list',
                'sub_category_class_insert',
                'sub_category_class_update',
                'sub_category_class_delete',
                'sub_category_class_print',
                'class',
                'class_list',
                'class_insert',
                'class_update',
                'class_delete',
                'class_print',
                'video',
                'video_list',
                'video_insert',
                'video_update',
                'video_delete',
                'video_print',
                'media_conference',
                'media_conference_list',
                'media_conference_insert',
                'media_conference_update',
                'media_conference_delete',
                'media_conference_print',
                'materi',
                'materi_list',
                'materi_insert',
                'materi_update',
                'materi_delete',
                'materi_print',
                'expertise',
                'expertise_list',
                'expertise_insert',
                'expertise_update',
                'expertise_delete',
                'expertise_print',
                'admin',
                'admin_list',
                'admin_insert',
                'admin_update',
                'admin_delete',
                'admin_print',
                'coach',
                'coach_list',
                'coach_insert',
                'coach_update',
                'coach_delete',
                'coach_print',
                'student',
                'student_list',
                'student_insert',
                'student_update',
                'student_delete',
                'student_print',
                'data_trasaction',
                'data_trasaction_coach',
                'data_trasaction_coach_list',
                'data_trasaction_coach_insert',
                'data_trasaction_coach_update',
                'data_trasaction_coach_delete',
                'data_trasaction_coach_print',
                'data_trasaction_student',
                'data_trasaction_student_list',
                'data_trasaction_student_insert',
                'data_trasaction_student_update',
                'data_trasaction_student_delete',
                'data_trasaction_student_print',
                'schedule',
                'reporting',
                'reporting_review',
                'reporting_review_coach_list',
                'reporting_review_coach_feedback_list',
                'reporting_review_student_list',
                'reporting_review_student_feedback_list',
                'reporting_review_class_list',
                'reporting_review_class_feedback_list',
                'reporting_review_video_list',
                'reporting_review_video_feedback_list',
                'reporting_transaction',
                'reporting_transaction_coach_list',
                'reporting_transaction_student_list',
                'notification_setting',
                'notification_setting_general',
                'notification_setting_general_list',
                'notification_setting_general_insert',
                'notification_setting_general_update',
                'notification_setting_general_delete',
                'notification_setting_general_print',
                'notification_setting_coach',
                'notification_setting_coach_list',
                'notification_setting_coach_insert',
                'notification_setting_coach_update',
                'notification_setting_coach_delete',
                'notification_setting_coach_print',
                'notification_setting_student',
                'notification_setting_student_list',
                'notification_setting_student_insert',
                'notification_setting_student_update',
                'notification_setting_student_delete',
                'notification_setting_student_print',
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
