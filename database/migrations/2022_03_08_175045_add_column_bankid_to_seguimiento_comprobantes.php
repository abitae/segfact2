<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBankidToSeguimientoComprobantes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('seguimiento_comprobantes', function (Blueprint $table) {
      $table->unsignedBigInteger('bank_id')->after('idBranchOffice')->nullable();
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
