<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateImageGaleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_galeries', function (Blueprint $table) {
            $table->string('link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<<<<<<<< HEAD:database/migrations/2024_01_18_094201_add_rating_to_session_videos.php
        Schema::table('session_videos', function (Blueprint $table) {
            $table->dropColumn('ratings');
========
        Schema::table('image_galeries', function (Blueprint $table) {
            $table->dropColumn('link');
>>>>>>>> origin:database/migrations/2022_01_20_154824_update_image_galeries_table.php
        });
    }
}
