<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourColumnsToSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
          $table->string('condicionPago',50)->nullable()->after('attachDocument');
          $table->string('nroGuiaRemision',50)->nullable()->after('condicionPago');
          $table->string('estadoEnvioSunat',50)->nullable()->after('nroGuiaRemision');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
}
