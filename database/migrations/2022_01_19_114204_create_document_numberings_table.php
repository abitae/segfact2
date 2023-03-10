<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentNumberingsTable extends Migration
{
  public function up()
  {
    Schema::create('document_numberings', function (Blueprint $table) {
      $table->id();
      $table->string('type',50);
      $table->bigInteger('id_enterprise');
      $table->bigInteger('id_user');
      $table->bigInteger('number');
      $table->integer('version');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('document_numberings');
  }
}
