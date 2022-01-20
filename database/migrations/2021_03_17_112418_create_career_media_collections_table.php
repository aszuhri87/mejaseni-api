<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerMediaCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_media_collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('career_collection_id');
            $table->string('url');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('career_collection_id')->references('id')->on('career_collections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_media_collections');
    }
}
