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
            $table->boolean('buy_btn_disable')->nullable()->default(false);
            $table->boolean('hide')->nullable()->default(false);
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
            $table->dropColumn('buy_btn_disable');
            $table->dropColumn('hide');
        });
    }
}
