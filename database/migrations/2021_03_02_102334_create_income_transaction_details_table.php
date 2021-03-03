<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_transaction_details');
    }
}
