<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_feedback', function (Blueprint $table) {
            $table->dropColumn('student_id');
            $table->uuid('student_schedule_id');

            $table->foreign('student_schedule_id')->references('id')->on('student_schedules');
        });

        Schema::table('session_feedback', function (Blueprint $table) {
            $table->dropColumn('session_id');
            $table->uuid('student_schedule_id');

            $table->foreign('student_schedule_id')->references('id')->on('student_schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_feedback', function (Blueprint $table) {
            $table->dropColumn('student_schedule_id');
            $table->uuid('student_id');

            $table->foreign('student_id')->references('id')->on('students');
        });

        Schema::table('session_feedback', function (Blueprint $table) {
            $table->dropColumn('student_schedule_id');
            $table->uuid('session_id');

            $table->foreign('session_id')->references('id')->on('sessions');
        });
    }
}
