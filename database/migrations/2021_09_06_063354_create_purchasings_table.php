<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasings', function (Blueprint $table) {
            $table->id();
            /*$table->string('orderNo');*/
            $table->string('purchaseOrderFile')->nullable(); //pdf file
            $table->string('purchaseType'); // product/service
            $table->string('grandTotal'); 
            $table->string('vat_not_vat'); 
            $table->string('seller_type')->nullable(); //seller_type
            $table->string('request_seller')->nullable(); //request seller
            $table->bigInteger('request_seller_id')->nullable(); //request seller id



            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('vat', 10, 2)->nullable();
            $table->decimal('net_total', 10, 2)->nullable();
            $table->date('date'); // date of purchase
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
        Schema::dropIfExists('purchasings');
    }
}
