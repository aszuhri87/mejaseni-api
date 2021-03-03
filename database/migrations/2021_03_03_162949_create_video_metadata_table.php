<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_metadata', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('theory_video_id');
            $table->string('url');
            $table->boolean('is_complete')->default(false);
            $table->integer('resolution');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('theory_video_id')->references('id')->on('theory_videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_metadata');
    }
}
