<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubCategoryDeleteBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_classroom_categories', function (Blueprint $table) {
            $table->dropColumn('default_banner');
            $table->dropColumn('banner_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_classroom_categories', function (Blueprint $table) {
            $table->string('default_banner')->nullable();
            $table->string('banner_message')->nullable();
        });
    }
}
