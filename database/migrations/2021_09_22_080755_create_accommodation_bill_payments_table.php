<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodation_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('accommodation_base_id');
            $table->decimal('amount', 10, 2);
            $table->string('billType');
            $table->string('billMonth');
            $table->string('attachment')->nullable();
            $table->string('date');
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('accommodation_bill_payments');
    }
}
