<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMasterLessonTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_lessons', function (Blueprint $table) {
            $table->uuid('classroom_category_id');
            $table->uuid('sub_classroom_category_id')->nullable();
            $table->integer('price');
            $table->dateTime('datetime');

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
        Schema::table('master_lessons', function (Blueprint $table) {
            $table->dropColumn('classroom_category_id');
            $table->dropColumn('sub_classroom_category_id');
            $table->dropColumn('price');
            $table->dropColumn('datetime');
        });
    }
}
