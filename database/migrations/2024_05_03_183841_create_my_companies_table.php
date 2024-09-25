<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_companies', function (Blueprint $table) {
            $table->id();
            $table->string('ruc');
            $table->string('RazonSocial');
            $table->string('plan');
            $table->string('monto');
            $table->string('deuda');
            $table->dateTime('fecha_suscription');
            $table->dateTime('fecha_certificacion');
            $table->dateTime('fin_suscription');
            $table->dateTime('fin_certificacion');
            $table->text('nota');
            $table->string('archivo')->nullable();
            $table->string('nombre');
            $table->string('telefono');
            $table->string('estado')->default('bg-toinstall');
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
        Schema::dropIfExists('my_companies');
    }
}
