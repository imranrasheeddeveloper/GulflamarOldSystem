<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AssetsManagment extends Migration
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
            $table->string('asset_name');
            $table->string('asset_type');
            $table->string('year');
            $table->string('model');
            $table->string('make');
            $table->string('capacity');
            $table->string('fuel_type');
            $table->string('chassis_number');
            $table->longText('legal_documents');
            $table->string('mileage');
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
