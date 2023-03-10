<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSale extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'compraSolesDolares' => 'required',
      'codigoFactura' => 'required|unique:sales,codigoFactura,'.$this->id,
      'idCustomer' => 'required',
      'idUsuario' => 'required',
      'descripcionBienServicio' => 'required',
      'fechaEmision' => 'required',
      'idTipoComprobante' => 'required',
      'monto' => 'required_if:compraSolesDolares,dolares',
      'tipoDeCambio' => 'required_if:compraSolesDolares,dolaresrequired',
      'montoVentaSoles' => 'required_if:compraSolesDolares,soles',
      'igv' => 'required',
      'montoTotal' => 'required',
      'listSeries' => 'required|min:3'
    ];
  }

  public function attributes()
  {
    return [
      'compraSolesDolares' => 'Compra en Soles/Dólares',
      'codigoFactura' => 'Código de factura',
      'idCustomer' => 'Cliente',
      'idUsuario' => 'Vendedor',
      'descripcionBienServicio' => 'Descripción bien y/o servicio',
      'fechaEmision' => 'Fecha emisión',
      'idTipoComprobante' => 'Tipo de comprobante',
      'monto' => 'Monto',
      'tipoDeCambio' => 'Tipo de cambio',
      'montoVentaSoles' => 'Sub total',
      'igv' => 'Igv',
      'montoTotal' => 'Monto total',
      'listSeries' => 'Series'
    ];
  }

  public function messages()
  {
    return [
      'listSeries.min' => 'El campo :attribute debe contener al menos 1 serie.'
    ];
  }
}
