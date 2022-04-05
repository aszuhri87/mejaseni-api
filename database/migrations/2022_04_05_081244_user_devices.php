<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('coach_id');
            $table->string('device_token');
            $table->string('manufacture')->nullable()->default(null);
            $table->string('system_version')->nullable()->default(null);
            $table->string('system_os')->nullable()->default(null);
            $table->string('imei')->nullable()->default(null);
            $table->string('os_name')->nullable()->default(null);
            $table->string('app_version')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('coach_id')
                ->references('id')
                ->on('coaches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_devices');
    }
}
