<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateVarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_vars', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('template_id')->nullable()->unsigned();
          $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');
          $table->integer('var_id')->nullable()->unsigned();
          $table->foreign('var_id')->references('id')->on('vars')->onDelete('set null');
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
        Schema::dropIfExists('template_vars');
    }
}
