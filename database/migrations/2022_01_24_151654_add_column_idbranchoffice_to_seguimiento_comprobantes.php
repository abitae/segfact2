<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdbranchofficeToSeguimientoComprobantes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('seguimiento_comprobantes', function (Blueprint $table) {
      $table->bigInteger('idBranchOffice')->nullable()->after('idUsuario');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('seguimiento_comprobantes', function (Blueprint $table) {
      //
    });
  }
}
