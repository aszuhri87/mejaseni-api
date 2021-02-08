<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_videos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sub_classroom_category_id');
            $table->string('name');
            $table->dateTime('datetime');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sub_classroom_category_id')->references('id')->on('sub_classroom_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_videos');
    }
}
