<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestStarMasterLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_star_master_lessons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('guest_star_id');
            $table->uuid('master_lesson_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('guest_star_id')->references('id')->on('guest_stars');
            $table->foreign('master_lesson_id')->references('id')->on('master_lessons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_star_master_lessons');
    }
}
