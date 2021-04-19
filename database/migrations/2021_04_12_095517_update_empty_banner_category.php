<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmptyBannerCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_categories', function (Blueprint $table) {
            $table->string('empty_image')->nullable();
            $table->string('empty_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classroom_categories', function (Blueprint $table) {
            $table->dropColumn('empty_image');
            $table->dropColumn('empty_message');
        });
    }
}
