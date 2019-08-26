<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluatedTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluated_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('score');
            $table->string('upload');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('task_id');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('task_id')->references('id')->on('tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluated_tasks');
    }
}
