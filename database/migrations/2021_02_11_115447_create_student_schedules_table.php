<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_classroom_id');
            $table->uuid('session_id');
            $table->uuid('coach_schedule_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_classroom_id')->references('id')->on('student_classrooms');
            $table->foreign('session_id')->references('id')->on('sessions');
            $table->foreign('coach_schedule_id')->references('id')->on('coach_schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_schedules');
    }
}
