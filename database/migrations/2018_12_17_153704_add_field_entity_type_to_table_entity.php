<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldEntityTypeToTableEntity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entities', function (Blueprint $table) {
          $table->integer('type_id')->after('name')->nullable()->unsigned();
          $table->foreign('type_id')->references('id')->on('types');
          $table->integer('entity_id')->after('type_id')->nullable()->unsigned();
          $table->foreign('entity_id')->references('id')->on('entities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign('entities_entity_id_foreign');
            $table->dropForeign('entities_type_id_foreign');
            $table->dropColumn('type_id');
            $table->dropColumn('entity_id');
        });
    }
}
