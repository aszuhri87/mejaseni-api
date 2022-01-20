<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoachNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_notifications', function (Blueprint $table) {
            $table->uuid('income_transaction_id')->nullable();

            $table->foreign('income_transaction_id')->references('id')->on('income_transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_transaction_id', function (Blueprint $table) {
            $table->dropColumn('column');
        });
    }
}
