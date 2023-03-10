<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsactivetrackingToSeguimientoComprobantes extends Migration
{
  public function up() {
    Schema::table('seguimiento_comprobantes', function (Blueprint $table) {
      $table->boolean('isActiveTracking')->default(0)->after('is_active');
    });
  }

  public function down() {
    Schema::table('seguimiento_comprobantes', function (Blueprint $table) {
      //
    });
  }
}
