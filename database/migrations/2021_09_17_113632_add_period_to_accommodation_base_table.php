<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodToAccommodationBaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accommodation_base', function (Blueprint $table) {
            $table->string('rooms')->change();

            $table->string('period')->nullable();
            $table->string('apartment')->nullable();
            $table->bigInteger('electricityAccountNo')->nullable();
            $table->bigInteger('waterBillAccountNo')->nullable();
            $table->string('contactName')->nullable();
            $table->string('contactNo')->nullable();
            $table->string('accountName')->nullable();
            $table->string('bankName')->nullable();
            $table->string('iban')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('fixedElectricityAmount')->nullable();
            $table->integer('fixedWaterAmount')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accommodation_base', function (Blueprint $table) {
           $table->dropColumn('period');
           $table->dropColumn('apartment');
           $table->dropColumn('electricityAccountNo');
           $table->dropColumn('waterBillAccountNo');
           $table->dropColumn('contactName');
           $table->dropColumn('contactNo');
           $table->dropColumn('accountName');
           $table->dropColumn('bankName');
           $table->dropColumn('iban');
           $table->dropColumn('fixedElectricityAmount');
           $table->dropColumn('fixedWaterAmount');
           $table->dropColumn('notes');
        });
    }
}
