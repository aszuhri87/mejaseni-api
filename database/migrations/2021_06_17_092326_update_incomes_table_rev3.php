<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIncomesTableRev3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('classroom_id');
            $table->dropColumn('transaction_id');

            $table->text('formula')->nullable();
            $table->uuid('student_schedule_id')->nullable();

            $table->foreign('student_schedule_id')->references('id')->on('student_schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->uuid('classroom_id')->nullable();
            $table->uuid('transaction_id')->nullable();

            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('transaction_id')->references('id')->on('transactions');

            $table->dropColumn('formula');
            $table->dropColumn('student_schedule_id');
        });
    }
}
