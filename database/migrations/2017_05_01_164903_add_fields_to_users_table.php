<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->after('email')->nullable();
            $table->string('position')->after('avatar')->nullable();
            $table->integer('city_id')->unsigned()->nullable()->after('position');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_city_id_foreign');
            $table->dropColumn('city_id');
            $table->dropColumn('position');
            $table->dropColumn('avatar');
        });
    }
}
