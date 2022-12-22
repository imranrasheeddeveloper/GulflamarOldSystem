<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id');
            // $table->unsignedBigInteger('account_id');
            // $table->foreign('account_id')->references('id')->on('expense_accounts')->onDelete('cascade');
            $table->bigInteger('transaction_id');
            // $table->unsignedBigInteger('transaction_id');
            // $table->foreign('transaction_id')->references('id')->on('patty_cashes')->onDelete('cascade');

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
        Schema::dropIfExists('expense_categories');
    }
}
