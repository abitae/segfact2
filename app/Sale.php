<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
  protected $fillable = [
    'stateDelivery',
    'warrantyStartDate',
    'warrantyPeriod',
    'warrantyPeriodQuantity',
    'codigoUnidadEjecutora',
    'nroSiaf'
  ];

  public function cliente(): BelongsTo {
    return $this->belongsTo(Customer::class, 'idCustomer', 'id');
  }

  public function tipoComprobante(): BelongsTo {
    return $this->belongsTo(TypeDocument::class, 'idTipoComprobante', 'id');
  }

  public function vendedor(): BelongsTo {
    return $this->belongsTo(User::class, 'idUsuario', 'id');
  }

  public function ventaDetalle(): HasMany {
    return $this->hasMany(SaleDetail::class, 'idSale', 'id');
  }

  /**
   * Get the user that owns the Sale
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function enterprise(): BelongsTo
  {
    return $this->belongsTo(Enterprise::class, 'idEnterprise', 'id');
  }

  /**
   * Get the user that owns the Sale
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function branchOffice(): BelongsTo
  {
      return $this->belongsTo(BranchOffice::class, 'idBranchOffice', 'id');
  }

}
