<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVideoTheoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theory_videos', function (Blueprint $table) {
            $table->dropColumn('url');
            $table->string('youtube_url')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_name_original')->nullable();
            $table->integer('duration')->nullable();
            $table->boolean('is_converter_complete')->default(false);
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
            $table->string('url')->nullable();
            $table->dropColumn('youtube_url');
            $table->dropColumn('file_name');
            $table->dropColumn('file_name_original');
            $table->dropColumn('duration');
            $table->dropColumn('is_converter_complete');
        });
    }
}
