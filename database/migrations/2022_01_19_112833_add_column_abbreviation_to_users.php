<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAbbreviationToUsers extends Migration
{
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('abbreviation')->nullable()->after('name');
    });
  }


  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      //
    });
  }
}
