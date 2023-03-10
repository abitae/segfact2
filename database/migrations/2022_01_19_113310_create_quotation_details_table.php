<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationDetailsTable extends Migration
{
  public function up()
  {
    Schema::create('quotation_details', function (Blueprint $table) {
      $table->id();
      $table->integer('quantity');
      $table->string('product_image',30);
      $table->string('product_name',255);
      $table->text('product_description');
      $table->decimal('unit_price',10,2);
      $table->decimal('total_amount',10,2);
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('quotation_details');
  }
}
