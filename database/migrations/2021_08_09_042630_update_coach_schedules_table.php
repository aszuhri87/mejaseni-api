<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoachSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_schedules', function (Blueprint $table) {
            $table->uuid('schedule_request_id')->nullable();

            $table->foreign('schedule_request_id')->references('id')->on('schedule_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach_schedules', function (Blueprint $table) {
            $table->dropColumn('schedule_request_id');
        });
    }
}
