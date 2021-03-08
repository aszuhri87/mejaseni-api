<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewsTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('classroom_category_id');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->uuid('classroom_category_id')->nullable();
            $table->foreign('classroom_category_id')->references('id')->on('classroom_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('classroom_category_id');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->uuid('classroom_category_id')->nullable();
            $table->foreign('classroom_category_id')->references('id')->on('classroom_categories');
        });
    }
}
