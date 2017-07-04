<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
        Schema::create('entity_goal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_id')->unsigned()->nullable();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->integer('goal_id')->unsigned()->nullable();
            $table->foreign('goal_id')->references('id')->on('goals')->onDelete('cascade');
            $table->double('value')->nullable()->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
            $table->unique(['entity_id','goal_id','date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_goal');
        Schema::dropIfExists('goals');
    }
}
