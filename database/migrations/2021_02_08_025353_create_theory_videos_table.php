<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheoryVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theory_videos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_video_id');
            $table->string('name');
            $table->boolean('is_youtube')->default(false);
            $table->string('url');
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
        Schema::dropIfExists('theory_videos');
    }
}
