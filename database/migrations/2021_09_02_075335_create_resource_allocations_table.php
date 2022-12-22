<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('resourceOwnerType');
            $table->string('resourceOwnerId'); // can be employee id, project(client) id or accommodation id
            $table->date('allocationDate')->nullable();
            $table->string('attachment')->nullable(); 
            $table->longText('signature')->nullable();
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
        Schema::dropIfExists('resource_allocations');
    }
}
