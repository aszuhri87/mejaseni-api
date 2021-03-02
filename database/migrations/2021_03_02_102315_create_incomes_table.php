<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('master_lesson_id')->nullable();
            $table->uuid('session_video_id')->nullable();
            $table->uuid('classroom_id')->nullable();
            $table->uuid('coach_id')->nullable();
            $table->uuid('theory_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('master_lesson_id')->references('id')->on('master_lessons');
            $table->foreign('session_video_id')->references('id')->on('session_videos');
            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('theory_id')->references('id')->on('theories');
            $table->foreign('coach_id')->references('id')->on('coaches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
