<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('status');
            $table->string('number')->unique();
            $table->boolean('confirmed')->default(false);
            $table->mediumText('json_transaction')->nullable();
            $table->longText('json_doku_notification')->nullable();
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
            $table->dropColumn('status');
            $table->dropColumn('number');
            $table->dropColumn('confirmed');
            $table->dropColumn('json_transaction');
            $table->dropColumn('json_doku_notification');
        });
    }
}
