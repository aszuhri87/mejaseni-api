<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('coach_classroom_id');
            $table->uuid('platform_id');
            $table->uuid('admin_id')->nullable();
            $table->boolean('accepted')->default(false);
            $table->dateTime('datetime');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('coach_classroom_id')->references('id')->on('coach_classrooms');
            $table->foreign('platform_id')->references('id')->on('platforms');
            $table->foreign('admin_id')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coach_schedules');
    }
}
