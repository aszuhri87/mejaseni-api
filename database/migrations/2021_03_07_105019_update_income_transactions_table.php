<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIncomeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_transactions', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('bank');
            $table->string('bank_number');
            $table->string('name_account');
            $table->dropColumn('confirmed_at');
        });

        Schema::table('income_transactions', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_transactions', function (Blueprint $table) {
            $table->dropColumn('confirmed_at')->nullable();
        });

        Schema::table('income_transactions', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('bank');
            $table->dropColumn('bank_number');
            $table->dropColumn('name_account');
            $table->timestamp('confirmed_at')->nullable();
        });
    }
}
