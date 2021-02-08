<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('classroom_category_id')->nullable();
            $table->uuid('sub_classroom_category_id')->nullable();
            $table->uuid('package_id')->nullable();
            $table->uuid('platform_id');
            $table->integer('type');
            $table->string('name');
            $table->text('description');
            $table->string('image');
            $table->double('price', 20, 2);
            $table->integer('session_total');
            $table->integer('session_duration');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('classroom_category_id')->references('id')->on('classroom_categories');
            $table->foreign('sub_classroom_category_id')->references('id')->on('sub_classroom_categories');
            $table->foreign('package_id')->references('id')->on('packages');
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
        Schema::dropIfExists('classrooms');
    }
}
