<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheoryVideoFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theory_video_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_video_id');
            $table->string('name');
            $table->string('url');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('session_video_id')->references('id')->on('session_videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theory_video_files');
    }
}
