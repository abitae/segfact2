<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIduserToOperationVoucherTrackings extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('operation_voucher_trackings', function (Blueprint $table) {
      $table->bigInteger('idUser')->nullable()->after(('idSale'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('operation_voucher_trackings', function (Blueprint $table) {
      //
    });
  }
}
