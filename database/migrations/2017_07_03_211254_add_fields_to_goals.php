<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToGoals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->string('modifier')->nullable()->after('description')->default('none');
            $table->double('weight')->nullable()->default(100)->after('modifier');
        });

        Schema::table('entity_goal', function (Blueprint $table) {
            $table->double('real')->after('value')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entity_goal', function (Blueprint $table) {
            $table->dropColumn('real');
        });
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('modifier');
        });
    }
}
