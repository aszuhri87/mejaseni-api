<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('expertise')->nullable()->change();
            $table->string('username')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
}
