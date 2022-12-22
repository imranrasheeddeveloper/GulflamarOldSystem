<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePattyCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patty_cashes', function (Blueprint $table) {
            $table->id();
            // $table->string('wallet_id');
            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->string('transaction_type');
            $table->string('assign_to_category')->nullable();
            $table->string('assign_to_entity')->nullable();
            $table->bigInteger('assign_to_entity_id')->nullable();
            $table->boolean('approve');
            $table->boolean('accept');


            $table->string('date');
            $table->decimal('unit_price',15,2);
            $table->decimal('quantity',15,2);
            $table->decimal('total',15,2);
            $table->string('credit_debit');


            $table->longText('description')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('attachment');
            $table->string('approved_by')->nullable();
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
        Schema::dropIfExists('patty_cashes');
    }
}
