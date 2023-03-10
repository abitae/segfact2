<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIdshoppingIdSaleToSeries extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('series', function (Blueprint $table) {
      $table->bigInteger('idShopping')->nullable()->after('id');
      $table->bigInteger('idSale')->nullable()->after('idShopping');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('series', function (Blueprint $table) {
      //
    });
  }
}
