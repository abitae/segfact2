<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAccountdetractionsToEnterprises extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('enterprises', function (Blueprint $table) {
      $table->string('nro_cuenta_detraction', 50)->nullable()->after('nro_cuenta_interbancaria');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('enterprises', function (Blueprint $table) {
      //
    });
  }
}
