<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguimientoComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimiento_comprobantes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idCustomer');
            $table->integer('idEnterprise');
            $table->bigInteger('idSale');
            $table->bigInteger('idShopping');
            $table->integer('idUsuario');
            $table->integer('codigoUnidadEjecutora')->nullable();
            $table->integer('nroSiaf')->nullable();
            $table->decimal('monto',10,2)->nullable();
            $table->text('descripcionBienServicio');
            $table->date('fechaEmision');
            $table->date('fechaVencimiento');
            $table->text('contactoEntidad')->nullable();
            $table->integer('estadoDocumento');
            $table->text('actionesObservaciones');
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
        Schema::dropIfExists('seguimiento_comprobantes');
    }
}
