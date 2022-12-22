<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoFieldInCallCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_centers', function (Blueprint $table) {
            $table->string('method_of_contact')->nullable();
            $table->string('contact_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_centers', function (Blueprint $table) {
            $table->dropColumn('method_of_contact');
            $table->dropColumn('contact_detail');
        });
    }
}
