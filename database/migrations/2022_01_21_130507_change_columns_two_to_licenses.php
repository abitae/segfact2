<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsTwoToLicenses extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('licenses', function (Blueprint $table) {
      $table->date('expirationDate')->nullable()->change();
      $table->bigInteger('idContact')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('licenses', function (Blueprint $table) {
      //
    });
  }
}
