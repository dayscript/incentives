<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vars', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name',100);
          $table->string('machine_name',150);
          $table->string('type')->default('single');
          $table->integer('client_id')->nullable()->unsigned();
          $table->foreign('client_id')->references('id')->on('clients');
          $table->string('value')->nullable();
          $table->integer('vars_one_id')->nullable()->unsigned();
          $table->foreign('vars_one_id')->references('id')->on('vars')->onDelete('set null');
          $table->integer('vars_two_id')->nullable()->unsigned();
          $table->foreign('vars_two_id')->references('id')->on('vars')->onDelete('set null');
          $table->softDeletes();
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
        Schema::dropIfExists('vars');
    }
}
