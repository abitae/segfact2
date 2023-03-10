<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SaleSeries extends Model
{
  public function serie(): HasOne
  {
    return $this->hasOne(Series::class, 'id', 'idSerie');
  }
}
