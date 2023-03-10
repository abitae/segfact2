<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function register()
  {
    Carbon::setUTF8(true);
    Carbon::setLocale(config('app.locale'));
    setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
    
   // $this->app->bind('path.public',function() {
    //    return'/home/atiqcorp/fulltecnologia.pe/segfact';
   // });
  }

  public function boot()
  {
    //
  }
}
