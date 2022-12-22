<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldInPattyCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patty_cashes', function (Blueprint $table) {
            $table->string('client_location')->nullable()->after('	assign_to_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patty_cashes', function (Blueprint $table) {
            $table->dropColumn('client_location');
        });
    }
}
