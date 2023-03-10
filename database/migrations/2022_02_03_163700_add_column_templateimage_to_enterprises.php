<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTemplateimageToEnterprises extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('enterprises', function (Blueprint $table) {
      $table->string('template', 150)->nullable()->after('nroPhone');
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
