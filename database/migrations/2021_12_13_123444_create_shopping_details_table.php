<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_details', function (Blueprint $table) {
          $table->id();
          $table->bigInteger('idShopping');
          $table->integer('cantidad');
          $table->text('descripcion');
          $table->decimal('precioUnitario',7,2);
          $table->decimal('importe',10,2);
          $table->boolean('is_active')->default(true);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_details');
    }
}
