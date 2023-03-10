<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShoppingSeries extends Model
{
  public function Serie(): HasOne
  {
    return $this->hasOne(Series::class, 'id', 'idSerie');
  }
}
