<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingDetail extends Model
{
  public function detailSeries(): HasMany
  {
    return $this->hasMany(ShoppingSeries::class, 'idSale', 'id');
  }
}
