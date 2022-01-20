<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheoryVideoResolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theory_video_resolutions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('theory_video_id');
            $table->string('name');
            $table->text('url');
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
        Schema::dropIfExists('theory_video_resolutions');
    }
}
