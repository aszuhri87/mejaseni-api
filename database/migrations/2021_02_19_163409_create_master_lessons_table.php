<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_lessons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('platform_id');
            $table->string('name');
            $table->string('poster');
            $table->integer('slot');
            $table->string('platform_link');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('platform_id')->references('id')->on('platforms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_lessons');
    }
}
