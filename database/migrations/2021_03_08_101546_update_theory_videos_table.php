<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTheoryVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theory_videos', function (Blueprint $table) {
            $table->integer('number')->nullable();
            $table->boolean('is_public')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theory_videos', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('is_public');
        });
    }
}
