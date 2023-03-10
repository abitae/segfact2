<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdenterpriseToShoppings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('shoppings', function (Blueprint $table) {
        $table->bigInteger('idEnterprise')->nullable()->after('idUsuario');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shoppings', function (Blueprint $table) {
            //
        });
    }
}
