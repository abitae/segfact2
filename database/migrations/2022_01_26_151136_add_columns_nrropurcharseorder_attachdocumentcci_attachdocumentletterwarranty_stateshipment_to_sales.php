<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsNrropurcharseorderAttachdocumentcciAttachdocumentletterwarrantyStateshipmentToSales extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('sales', function (Blueprint $table) {
      $table->string('nroPucharseOrder',50)->nullable()->after('nroGuiaRemision');
      $table->string('documentCci',90)->nullable()->after('attachRemissionGuideDocument');
      $table->string('documentLetterWarranty',90)->nullable()->after('documentCci');
      $table->smallInteger('stateDelivery')->nullable()->comment('1 => PorDespachar, 2 => Despachado, 3 => Entregado')->after('documentLetterWarranty');
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
