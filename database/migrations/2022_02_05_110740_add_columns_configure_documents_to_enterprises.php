<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsConfigureDocumentsToEnterprises extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('enterprises', function (Blueprint $table) {
      $table->string('representative_name', 250)->nullable()->after('template');
      $table->string('representative_dni', 10)->nullable()->after('representative_name');
      $table->string('representative_ruc', 15)->nullable()->after('representative_dni');
      $table->string('nro_cuenta_interbancaria', 50)->nullable()->after('representative_ruc');
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
