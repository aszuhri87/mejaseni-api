<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('coach_id');
            $table->uuid('transaction_id')->nullable();
            $table->uuid('coach_schedule_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->integer('type');
            $table->dateTime('datetime')->default(date('Y-m-d H:i:s'));
            $table->text('text');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('coach_id')->references('id')->on('coaches');
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('coach_schedule_id')->references('id')->on('coach_schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coach_notifications');
    }
}
