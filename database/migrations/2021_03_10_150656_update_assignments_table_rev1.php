<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAssignmentsTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('due_time')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->datetime('due_date')->nullable();
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('due_time');
        });
    }
}
