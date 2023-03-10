<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SeguimientoComprobante extends Model
{
  public $fillable = [
    'isActiveTracking',
    'codigoUnidadEjecutora',
    'nroSiaf',
    'actionesObservaciones',
    'arranged',
  ];

  public function usuario(): BelongsTo
  {
    return $this->belongsTo(User::class, 'idUsuario', 'id');
  }

  public function cliente(): BelongsTo
  {
    return $this->belongsTo(Customer::class, 'idCustomer', 'id');
  }

  public function estadoDeDocumento(): BelongsTo
  {
    return $this->belongsTo(DocumentState::class, 'estadoDocumento', 'id');
  }

  public function facturaVenta(): BelongsTo
  {
    return $this->belongsTo(Sale::class,'idSale','id');
  }

  public function empresa(): BelongsTo
  {
    return $this->belongsTo(Enterprise::class,'idEnterprise','id');
  }

  public function bank(): HasOne
  {
      return $this->hasOne(Bank::class, 'id', 'bank_id');
  }
}
