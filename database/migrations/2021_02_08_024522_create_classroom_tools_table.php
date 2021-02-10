<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_tools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('classroom_id');
            $table->uuid('tool_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('tool_id')->references('id')->on('tools');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_tools');
    }
}
