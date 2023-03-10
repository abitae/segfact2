<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shopping extends Model
{
  public function proveedor(): BelongsTo
  {
    return $this->belongsTo(Supplier::class, 'idSupplier', 'id');
  }

  public function tipoComprobante(): BelongsTo
  {
    return $this->belongsTo(TypeDocument::class, 'idTipoComprobante', 'id');
  }

  public function detail(): HasMany
  {
      return $this->hasMany(ShoppingDetail::class, 'idShopping', 'id');
  }
}
