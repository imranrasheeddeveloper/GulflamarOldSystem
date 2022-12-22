<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('name_id');

            $table->longText('project');
            $table->string('date');
            $table->string('time');
            $table->longText('issue');
            $table->bigInteger('department');
            $table->string('status');
            $table->longText('issue_resolution')->nullable();
            $table->longText('resolution');
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
        Schema::dropIfExists('call_centers');
    }
}
