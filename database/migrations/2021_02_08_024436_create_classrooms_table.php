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
            $table->integer('package_type')->nullable();
            $table->integer('sub_package_type')->nullable();
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
