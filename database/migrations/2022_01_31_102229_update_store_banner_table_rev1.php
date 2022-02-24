<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStoreBannerTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_banners', function (Blueprint $table) {
            $table->string('url_link')->nullable();
            $table->boolean('show')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_banners', function (Blueprint $table) {
            $table->dropColumn('url_link');
            $table->dropColumn('show');
        });
    }
}
