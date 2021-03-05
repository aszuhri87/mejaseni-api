<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionVideoFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_video_feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_video_id');
            $table->uuid('student_id');
            $table->integer('star');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('session_video_id')->references('id')->on('session_videos');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_video_feedback');
    }
}
