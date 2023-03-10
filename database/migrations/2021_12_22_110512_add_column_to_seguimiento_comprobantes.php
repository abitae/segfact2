<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSeguimientoComprobantes extends Migration
{
    public function up()
    {
      Schema::table('seguimiento_comprobantes', function (Blueprint $table) {
        $table->decimal('detraccion',10,2)->nullable()->after('monto');
        $table->decimal('retencion',10,2)->nullable()->after('detraccion');
        $table->decimal('montoTotal',10,2)->after('retencion');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimiento_comprobantes', function (Blueprint $table) {
            //
        });
    }
}
