<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_schedules', function (Blueprint $table) {
            $table->dateTime('check_in')->nullable();
        });

        Schema::table('theories', function (Blueprint $table) {
            $table->uuid('created_by')->nullable();

            $table->foreign('created_by')->references('id')->on('coaches');
        });

        Schema::table('classroom_categories', function (Blueprint $table) {
            $table->uuid('profile_coach_video_id')->nullable();

            $table->foreign('profile_coach_video_id')->references('id')->on('profile_coach_videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_schedules', function (Blueprint $table) {
            $table->dropColumn('check_in');
        });

        Schema::table('theories', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('classroom_categories', function (Blueprint $table) {
            $table->dropColumn('profile_coach_video_id');
        });
    }
}
