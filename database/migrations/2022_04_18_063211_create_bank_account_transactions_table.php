<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_transactions', function (Blueprint $table) {
            $table->id();
            // $table->string('bank_account_id');
            $table->unsignedBigInteger('bank_account_id');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->string('transaction_type');
            $table->string('date');
            $table->string('sub_type')->nullable();
            $table->longText('to_from')->nullable();
            $table->longText('notes')->nullable();
            $table->decimal('amount',15,2);
            $table->string('credit_debit')->nullable();

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
        Schema::dropIfExists('bank_account_transactions');
    }
}
