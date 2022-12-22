<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodation_payments', function (Blueprint $table) {
            $table->id();
            $table->string('accommodation_base_id');
            $table->decimal('amount', 10, 2);
            $table->string('date');
            $table->string('attachment')->nullable();
            $table->string('rent_reciept')->nullable();
            $table->longText('notes')->nullable();
            $table->string('periodFrom'); //date
            $table->string('periodTo'); //date
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
        Schema::dropIfExists('accommodation_payments');
    }
}
