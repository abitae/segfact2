<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchOfficesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('branch_offices', function (Blueprint $table) {
      $table->id();
      $table->string('name',255)->nullable();
      $table->text('address')->nullable();
      $table->string('idUbigeo',20)->nullable();
      $table->string('nroPhone',20)->nullable();
      $table->string('email',150)->nullable();
      $table->bigInteger('idMycompany')->nullable();
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
    Schema::dropIfExists('branch_offices');
  }
}
