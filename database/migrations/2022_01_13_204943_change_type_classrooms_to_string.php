<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeClassroomsToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            if(Schema::hasColumn('classrooms','type')){

                Schema::table('classrooms', function (Blueprint $table) {
                    $table->dropColumn('type');
                });
    
            }
            Schema::table('classrooms', function (Blueprint $table) {
                $table->integer('type')->default(1);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('string', function (Blueprint $table) {
            //
        });
    }
}
