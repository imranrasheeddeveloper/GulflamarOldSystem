<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_type');
            $table->string('recipient');
            $table->longText('description')->nullable();
            $table->longText('attachment');
            $table->bigInteger('resource_owner_id');
            $table->longText('resource_owner_name');
            $table->string('date');
            $table->string('method');
            $table->string('received_by');
            $table->string('received_at');

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
        Schema::dropIfExists('documents');
    }
}
