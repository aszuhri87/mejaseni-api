<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentNotificationsTableRev1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_notifications', function (Blueprint $table) {
            $table->string('text_title')->nullable();
            $table->string('to_page')->nullable();
            $table->string('icon_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_notifications', function (Blueprint $table) {
            $table->dropColumn('text_title');
            $table->dropColumn('to_page');
            $table->dropColumn('icon_url');
        });
    }
}
