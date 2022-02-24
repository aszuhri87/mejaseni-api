<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateScheduleRequestsTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_requests', function (Blueprint $table) {
            $table->timestamp('datetime_previous')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_requests', function (Blueprint $table) {
            $table->dropColumn('datetime_previous');
        });
    }
}
