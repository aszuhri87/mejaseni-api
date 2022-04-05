<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id')->nullable();
            $table->uuid('coach_id')->nullable();
            $table->uuid('schedule_request_id')->nullable();
            $table->uuid('cart_id')->nullable();
            $table->boolean('coach_confirmed')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('schedule_request_id')
                ->references('id')
                ->on('schedule_requests')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('coach_id')
                ->references('id')
                ->on('coaches')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
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
        Schema::dropIfExists('student_requests');
    }
}
