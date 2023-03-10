<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSunatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sunats', function (Blueprint $table) {
            $table->id();
            $table->decimal('tipoCambioCompra',7,3)->nullable();
            $table->decimal('tipoCambioVenta',7,3)->nullable();
            $table->date('today');
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
        Schema::dropIfExists('sunats');
    }
}
