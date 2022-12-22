<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'title',
    // 'description',
    // 'owner_id',
    // 'assign_to',
    // 'due_date',
    // 'tag',
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->longText('description');
            $table->bigInteger('ownerId');
            $table->bigInteger('assignTo');
            $table->string('dueDate');
            $table->string('tag');
            $table->boolean('isCompleted')->default(0);
            $table->boolean('isImportant')->default(0);
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
        Schema::dropIfExists('tasks');
    }
}
