<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingToSessionVideos extends Migration
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
            $table->integer('ratings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('session_videos', function (Blueprint $table) {
            $table->dropColumn('ratings');

            $table->dropColumn('link');

        });
    }
}
