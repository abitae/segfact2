<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{

  public function up()
  {
    Schema::create('quotations', function (Blueprint $table) {
      $table->id();
      $table->boolean('type_contact')->comment('0 => Documento, 1 => Verbal');
      $table->string('file_contact')->nullable();
      $table->bigInteger('id_enterprise');
      $table->bigInteger('id_customer');
      $table->bigInteger('id_user');
      $table->string('code',150);
      $table->text('description')->nullable();
      $table->decimal('total_amount',10,2);
      $table->smallInteger('state')->comment('1 => Emitido, 2 => Modificado, 3 => Ganado, 4 => Perdido');
      $table->string('delivery_time', 50)->nullable();
      $table->string('valid_offert', 50)->nullable();
      $table->string('warranty', 50)->nullable();
      $table->string('place_delivery', 100)->nullable();
      $table->string('form_payment',100)->nullable();
      $table->string('freight_charge')->default('CERO');
      $table->text('message')->nullable();
      $table->boolean('is_principal')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('quotations');
  }
}
