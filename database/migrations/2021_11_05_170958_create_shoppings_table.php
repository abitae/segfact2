<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('shoppings', function (Blueprint $table) {
        $table->id();
        $table->string('codigoFactura',30);
        $table->integer('idSupplier');
        $table->integer('idUsuario');
        $table->text('descripcionBienServicio');
        $table->date('fechaEmision');
        $table->integer('idTipoComprobante');
        $table->string('compraSolesDolares',10);
        $table->decimal('monto',10,2)->nullable();
        $table->decimal('tipoDeCambio',10,3)->nullable();
        $table->decimal('montoVentaSoles',10,2)->nullable();
        $table->decimal('montoVentaDolares',10,2)->nullable();
        $table->decimal('igv',10,2);
        $table->decimal('montoTotal',10,2);
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
        Schema::dropIfExists('shoppings');
    }
}
