<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationVoucherTrackingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('operation_voucher_trackings', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('idSale');
      $table->string('nroComprobante',50);
      $table->smallInteger('state');
      $table->string('observation',200)->default('Sin observaciones');
      $table->string('refactoringCode',50)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('operation_voucher_trackings');
  }
}
