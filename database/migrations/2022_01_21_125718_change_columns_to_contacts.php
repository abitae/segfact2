<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToContacts extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('contacts', function (Blueprint $table) {
      $table->string('typeDocument',2)->nullable()->change();
      $table->string('nroDocument',15)->nullable()->change();
      $table->string('fullName',250)->nullable()->change();
      $table->string('email',50)->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('contacts', function (Blueprint $table) {
      //
    });
  }
}
