<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('entity_id')->nullable()->unsigned();
            // $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->string('mail')->nullable();
            $table->string('telephone')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        Schema::create('entity_information', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_id')->nullable()->unsigned();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->integer('information_id')->nullable()->unsigned();
            $table->foreign('information_id')->references('id')->on('information')->onDelete('cascade');
            // $table->string('mail')->nullable();
            // $table->string('telephone')->nullable();
            // $table->boolean('status')->default(false);
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

      Schema::table('entity_information', function (Blueprint $table) {
        $table->dropForeign('entity_information_entity_id_foreign');
        $table->dropForeign('entity_information_information_id_foreign');
      });

      Schema::dropIfExists('information');
      Schema::dropIfExists('entity_information');

    }
}
