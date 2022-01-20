<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubClassroomCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_classroom_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('classroom_category_id');
            $table->uuid('profile_coach_video_id')->nullable();
            $table->string('name');
            $table->string('image');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('classroom_category_id')->references('id')->on('classroom_categories');
            $table->foreign('profile_coach_video_id')->references('id')->on('profile_coach_videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_classroom_categories');
    }
}
