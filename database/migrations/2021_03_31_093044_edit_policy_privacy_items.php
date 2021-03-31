<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditPolicyPrivacyItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privacy_policy_items', function (Blueprint $table) {
            $table->longText('quill_description')->nullable();
            $table->longText('json_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privacy_policy_items', function (Blueprint $table) {
            $table->dropColumn('quill_description');
            $table->dropColumn('json_description');
        });
    }
}
