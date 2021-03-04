<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->text('location');
            $table->boolean('is_free');
            $table->double('total');
            $table->integer('quota');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->date('date');
            $table->dropColumn('start_at');
            $table->dropColumn('end_at');
            $table->dropColumn('location');
            $table->dropColumn('is_free');
            $table->dropColumn('total');
            $table->dropColumn('quota');
        });
    }
}
