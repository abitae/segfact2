<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdenterpriseToBranchOffices extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('branch_offices', function (Blueprint $table) {
      $table->bigInteger('idEnterprise')->nullable()->after('idMycompany');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('branch_offices', function (Blueprint $table) {
      //
    });
  }
}
