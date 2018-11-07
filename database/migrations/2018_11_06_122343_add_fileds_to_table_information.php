<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledsToTableInformation extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      Schema::table('information', function (Blueprint $table) {
        $table->string('roles')->nullable()->after('id');
        $table->string('gender')->nullable()->after('id');
        $table->string('birthdate')->nullable()->after('id');
        $table->boolean('acepto_politica_de_datos_p')->nullable()->after('id');
        $table->boolean('acepto_terminos_y_condicio')->nullable()->after('id');
        $table->string('no_identificacion_asesor')->nullable()->after('id');
        $table->string('asesor')->nullable()->after('id');
        $table->string('no_identificacion')->nullable()->after('id');
        $table->string('apellidos')->nullable()->after('id');
        $table->string('nombres')->nullable()->after('id');
        $table->string('zoho_id')->nullable()->after('id');
        $table->string('zoho_module')->nullable()->after('id');
        $table->string('pass')->nullable()->after('id');
        $table->string('name')->nullable()->after('id');
     });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::table('information', function (Blueprint $table) {
        $table->dropColumn('name');
        $table->dropColumn('pass');
        $table->dropColumn('nombres');
        $table->dropColumn('apellidos');
        $table->dropColumn('no_identificacion');
        $table->dropColumn('acepto_terminos_y_condicio');
        $table->dropColumn('acepto_politica_de_datos_p');
        $table->dropColumn('birthdate');
        $table->dropColumn('gender');
        $table->dropColumn('roles');
        $table->dropColumn('asesor');
        $table->dropColumn('no_identification_asesor');
        $table->dropColumn('zoho_id');
        $table->dropColumn('zoho_module');
      });
  }
}
