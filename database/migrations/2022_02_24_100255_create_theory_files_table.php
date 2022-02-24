<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheoryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theory_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('theory_id');
            $table->string('url');
            $table->string('mime_type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('theory_id')
                ->references('id')
                ->on('theories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theory_files');
    }
}
