<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIdbranchofficeIdenterpriseVoidedvoucherCodeToSales extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('sales', function (Blueprint $table) {
      $table->bigInteger('idEnterprise')->after('codigoFactura');
      $table->bigInteger('idBranchOffice')->after('idEnterprise');
      $table->string('voidedVoucherCode',50)->after('estadoEnvioSunat')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('sales', function (Blueprint $table) {
      //
    });
  }
}
