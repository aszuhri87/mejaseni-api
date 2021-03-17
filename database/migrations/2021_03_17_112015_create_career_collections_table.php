<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('career_id');
            $table->dateTime('date');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('career_id')->references('id')->on('careers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_collections');
    }
}
