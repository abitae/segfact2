<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
          $table->id();
          $table->bigInteger('idShoppingMyCompany');
          $table->string('typeDocument',2);
          $table->string('nroDocument',15);
          $table->string('name',50)->nullable();
          $table->string('lastName',50)->nullable();
          $table->string('fullName',250);
          $table->text('address')->nullable();
          $table->string('email',50);
          $table->string('nroPhone',15);
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
        Schema::dropIfExists('suppliers');
    }
}
