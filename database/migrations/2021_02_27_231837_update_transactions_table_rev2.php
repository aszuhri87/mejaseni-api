<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTransactionsTableRev2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_type')->nullable();
            $table->string('payment_chanel')->nullable();
            $table->string('payment_url')->nullable();
            $table->dateTime('confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_chanel');
            $table->dropColumn('payment_url');
            $table->dropColumn('confirmed_at');
        });
    }
}
