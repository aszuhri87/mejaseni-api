<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIncomeTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('income_transaction_details');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('income_transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('income_transaction_id');
            $table->double('amount');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('income_transaction_id')->references('id')->on('income_transactions');
        });
    }
}
