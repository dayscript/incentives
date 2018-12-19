<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsZohoToTableEntities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('entities', function (Blueprint $table) {
        $table->string('zoho_id')->after('entity_id')->nullable();
        $table->string('zoho_module')->after('zoho_id')->nullable();
        $table->integer('zoho_lead_to_contact')->after('zoho_module')->nullable();
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
        $table->dropColumn('zoho_id');
        $table->dropColumn('zoho_module');
        $table->dropColumn('zoho_lead_to_contact');
      });
    }
}
