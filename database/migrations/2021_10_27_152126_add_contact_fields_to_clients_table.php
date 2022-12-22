<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactFieldsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_base', function (Blueprint $table) {
           $table->string('contactName')->nullable();
            $table->string('contactNo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_base', function (Blueprint $table) {
            $table->dropColumn('contactName');
            $table->dropColumn('contactNo');
        });
    }
}
