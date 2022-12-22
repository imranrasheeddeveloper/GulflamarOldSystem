<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class assetWorkOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_managments', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id');
            $table->timestamps('fromDate');
            $table->timestamps('toDate');
            $table->string('c_id');
            $table->string('location_id');
            $table->string('rate_basis');
            $table->string('rate');
            $table->string('check_out_reading');
            $table->string('check_in_reading');
            $table->string('scope');
            $table->longText('notes');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
