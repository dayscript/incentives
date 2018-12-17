<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEntityTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_type', function (blueprint $table){
          $table->increments('id');
          $table->integer('entity_id')->nullable()->unsigned();
          $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
          $table->integer('type_id')->nullable()->unsigned();
          $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
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
        Schema::dropIfExists('entity_type');
    }
}
