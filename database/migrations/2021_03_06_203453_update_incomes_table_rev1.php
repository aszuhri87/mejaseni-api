<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIncomesTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('master_lesson_id');
            $table->dropColumn('theory_id');
            $table->dropColumn('guest_star_id');
            $table->dropColumn('student_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->uuid('master_lesson_id')->nullable();
            $table->uuid('theory_id')->nullable();
            $table->uuid('guest_star_id')->nullable();
            $table->uuid('student_schedule_id')->nullable();

            $table->foreign('master_lesson_id')->references('id')->on('master_lessons');
            $table->foreign('theory_id')->references('id')->on('theories');
            $table->foreign('guest_star_id')->references('id')->on('guest_stars');
            $table->foreign('student_schedule_id')->references('id')->on('student_schedules');
        });
    }
}
