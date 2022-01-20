<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSessionVideosTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_videos', function (Blueprint $table) {
            $table->uuid('expertise_id');
            $table->uuid('coach_id');
            $table->text('description');
            $table->double('price', 20, 2);

            $table->foreign('expertise_id')->references('id')->on('expertises');
            $table->foreign('coach_id')->references('id')->on('coaches');
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
            $table->dropColumn('coach_id');
            $table->dropColumn('expertise_id');
            $table->dropColumn('description');
            $table->dropColumn('price');
        });
    }
}
