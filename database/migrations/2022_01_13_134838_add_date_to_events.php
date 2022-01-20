<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('events','date')){

            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('date');
            });

        }
        Schema::table('events', function (Blueprint $table) {
            $table->timestamp('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('events','date')){

            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('date');
            });

        }
       
    }
}
