<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsWarrantyperiodWarrantyquantityToSales extends Migration
{

  public function up()
  {
    Schema::table('sales', function (Blueprint $table) {
      $table->string('warrantyPeriod','20')->nullable()->after('warrantyStartDate');
      $table->integer('warrantyPeriodQuantity')->nullable()->after('warrantyPeriod');
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
