<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTrackingsTable extends Migration
{
  public function up()
  {
    Schema::create('quotation_trackings', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('id_quotation_principal');
      $table->bigInteger('id_quotation');
      $table->dateTime('tracking_date');
      $table->text('description');
      $table->integer('day');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('quotation_trackings');
  }
}
