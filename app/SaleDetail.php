<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleDetail extends Model
{
  public function listSeries(): HasMany
  {
    return $this->hasMany(SaleSeries::class, 'idSaleDetail', 'id');
  }
}
