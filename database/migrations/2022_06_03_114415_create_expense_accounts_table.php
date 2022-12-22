<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('month');
            $table->string('year');
            $table->decimal('credit',15,2)->nullable();
            $table->decimal('adjustment',15,2)->nullable();
            $table->decimal('fuel_credit',15,2)->nullable();

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
        Schema::dropIfExists('expense_accounts');
    }
}
