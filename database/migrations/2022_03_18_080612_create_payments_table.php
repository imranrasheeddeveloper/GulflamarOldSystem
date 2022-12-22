<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('request_payment_id');
            $table->string('request_type');
            $table->longText('request_owner');
            $table->bigInteger('request_owner_id');
            $table->string('payment_type');
            $table->decimal('amount',15,2);
            $table->string('status');
            $table->longText('attachment')->nullable();  // This is a JSON field
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
