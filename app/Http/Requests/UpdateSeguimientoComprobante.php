<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeguimientoComprobante extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      // 'fechaEmision' => 'required',
      // 'idShopping' => 'required',
      // 'idSale' => 'required',
      'monto' => 'required|min:0.10',
      // 'estadoDocumento' => 'required',
      // 'descripcionBienServicio' => 'required',
      // 'actionesObservaciones' => 'required',
      // 'idCustomer' => 'required',
    ];
  }

  public function attributes()
  {
    return [
      'fechaEmision' => 'Fecha emisión',
      'idShopping' => 'N° Factura Compra',
      'idSale' => 'N° Factura Venta',
      'monto' => 'Monto',
      'estadoDocumento' => 'Estado de orden',
      'descripcionBienServicio' => 'Descripción bien y/o servicio',
      'actionesObservaciones' => 'Acciones y/o observaciones',
      'idCustomer' => 'DNI/RUC Cliente',
    ];
  }
}
