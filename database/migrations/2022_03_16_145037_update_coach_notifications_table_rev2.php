<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoachNotificationsTableRev2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_notifications', function (Blueprint $table) {
            $table->uuid('student_request_id')->nullable();
            $table->foreign('student_request_id')
            ->references('id')
            ->on('student_requests')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach_notifications', function (Blueprint $table) {
            $table->dropColumn('student_request_id');
        });
    }
}
