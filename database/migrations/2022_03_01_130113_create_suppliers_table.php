<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('name_ar');
            $table->string('cr_number')->nullable(); 
            $table->string('vat_number'); 
            $table->string('contact_name')->nullable(); 
            $table->string('contact_number')->nullable();
            $table->string('city')->nullable(); 
            $table->string('bank_name')->nullable(); 
            $table->string('account_name')->nullable(); 
            $table->string('iban')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
