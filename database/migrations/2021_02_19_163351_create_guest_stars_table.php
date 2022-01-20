<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestStarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_stars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('coach_id')->nullable();
            $table->uuid('expertise_id');
            $table->string('name');
            $table->string('image');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('coach_id')->references('id')->on('coaches');
            $table->foreign('expertise_id')->references('id')->on('expertises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_stars');
    }
}
