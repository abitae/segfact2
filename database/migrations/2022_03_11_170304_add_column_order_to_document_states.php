<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOrderToDocumentStates extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('document_states', function (Blueprint $table) {
      $table->smallInteger('order')->nullable()->after('descripcion');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('document_states', function (Blueprint $table) {
      //
    });
  }
}
